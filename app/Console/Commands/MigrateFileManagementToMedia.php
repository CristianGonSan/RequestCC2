<?php

namespace App\Console\Commands;

use App\Models\MoneyRequests\FileManagement;
use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateFileManagementToMedia extends Command
{
    protected $signature = 'files:migrate-to-media
        {--source-disk= : Disco donde están los archivos viejos (por defecto: disco default)}
        {--media-disk= : Disco destino de Media Library (por defecto: config media-library.disk_name)}
        {--collection=attachments : Colección de Media Library donde se guardarán}
        {--chunk=100 : Registros procesados por lote}
        {--keep-original : Copiar en vez de mover (no borra el archivo viejo)}
        {--delete-record : Borrar el registro de file_management tras migrar con éxito}
        {--dry-run : Simular sin tocar archivos ni base de datos}';

    protected $description = 'Migra los registros de FileManagement a Spatie Media Library y mueve los archivos a la nueva ruta.';

    private int $migrated = 0;

    private int $skipped = 0;

    private int $failed = 0;

    public function handle(): int
    {
        $sourceDisk   = $this->option('source-disk') ?: config('filesystems.default');
        $mediaDisk    = $this->option('media-disk') ?: config('media-library.disk_name');
        $collection   = (string) $this->option('collection');
        $dryRun       = (bool) $this->option('dry-run');
        $keepOriginal = (bool) $this->option('keep-original');
        $deleteRecord = (bool) $this->option('delete-record');

        $total = FileManagement::count();

        $this->info("Registros a procesar: {$total}");
        $this->line("Disco origen: {$sourceDisk} | Disco Media: {$mediaDisk} | Colección: {$collection}");

        if ($dryRun) {
            $this->warn('Modo DRY-RUN: no se modificará nada.');
        } elseif (! $this->confirm('¿Continuar con la migración? Se recomienda tener un backup.', false)) {
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        FileManagement::query()
            ->orderBy('id')
            ->chunkById((int) $this->option('chunk'), function (Collection $files) use (
                $sourceDisk, $collection, $dryRun, $keepOriginal, $deleteRecord, $bar
            ) {
                // Se cargan por money_request_id explícitamente (ver nota sobre la relación `request()`).
                $requests = MoneyRequest::whereIn('id', $files->pluck('money_request_id')->unique())
                    ->get()
                    ->keyBy('id');

                foreach ($files as $file) {
                    $this->processFile(
                        $file,
                        $requests->get($file->money_request_id),
                        $sourceDisk,
                        $collection,
                        $dryRun,
                        $keepOriginal,
                        $deleteRecord,
                    );
                    $bar->advance();
                }
            });

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Migrados', 'Omitidos', 'Fallidos'],
            [[$this->migrated, $this->skipped, $this->failed]]
        );

        return $this->failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function processFile(
        FileManagement $file,
        ?MoneyRequest $request,
        string $sourceDisk,
        string $collection,
        bool $dryRun,
        bool $keepOriginal,
        bool $deleteRecord,
    ): void {
        if (! $request) {
            $this->logIssue("#{$file->id}: MoneyRequest {$file->money_request_id} no existe.");
            $this->skipped++;

            return;
        }

        // Idempotencia: si ya fue migrado, se omite.
        $alreadyMigrated = $request->media()
            ->where('collection_name', $collection)
            ->where('custom_properties->legacy_file_management_id', $file->id)
            ->exists();

        if ($alreadyMigrated) {
            $this->skipped++;

            return;
        }

        if (! Storage::disk($sourceDisk)->exists($file->file_path)) {
            $this->logIssue("#{$file->id}: archivo no encontrado en [{$sourceDisk}] {$file->file_path}");
            $this->failed++;

            return;
        }

        if ($dryRun) {
            $this->migrated++;

            return;
        }

        try {
            $fileName = $file->original_name ?: basename($file->file_path);

            $adder = $request
                ->addMediaFromDisk($file->file_path, $sourceDisk)
                ->usingName(pathinfo($fileName, PATHINFO_FILENAME))
                ->usingFileName($fileName)
                ->withCustomProperties([
                    'uploaded_by'               => $file->user_id,
                    'legacy_file_management_id' => $file->id,
                    'legacy_file_path'          => $file->file_path,
                ]);

            // Sin preservingOriginal(), Spatie elimina el archivo del disco origen tras copiarlo (= mover).
            if ($keepOriginal) {
                $adder->preservingOriginal();
            }

            // El 2º argumento debe ser string: '' usa el disco por defecto de Media Library.
            $media = $adder->toMediaCollection($collection, (string) ($this->option('media-disk') ?: ''));

            // Conservar fechas originales.
            $media->timestamps = false;
            $media->forceFill([
                'created_at' => $file->created_at,
                'updated_at' => $file->updated_at,
            ])->save();

            if ($deleteRecord) {
                $file->delete();
            }

            $this->migrated++;
        } catch (Throwable $e) {
            $this->logIssue("#{$file->id}: {$e->getMessage()}");
            $this->failed++;
        }
    }

    private function logIssue(string $message): void
    {
        $this->newLine();
        $this->error($message);
    }
}
