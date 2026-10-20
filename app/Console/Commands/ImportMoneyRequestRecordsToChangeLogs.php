<?php

namespace App\Console\Commands;

use App\Enums\Common\ChangeLogEvent;
use App\Models\Common\ChangeLog;
use App\Models\MoneyRequests\MoneyRequest;
use App\Models\MoneyRequests\MoneyRequestRecords;
use Illuminate\Console\Command;

class ImportMoneyRequestRecordsToChangeLogs extends Command
{
    protected $signature = 'money-requests:import-legacy-records
        {--chunk=500 : Number of legacy records to process per chunk}
        {--dry-run : Report what would be imported without writing changes}';

    protected $description = 'Import legacy money request records into the change log';

    public function handle(): int
    {
        $chunkSize = filter_var($this->option('chunk'), FILTER_VALIDATE_INT);

        if ($chunkSize === false || $chunkSize < 1) {
            $this->error('The --chunk option must be a positive integer.');

            return self::FAILURE;
        }

        $dryRun             = $this->option('dry-run');
        $imported           = 0;
        $alreadyImported    = 0;
        $unsupportedActions = 0;
        $subjectType        = (new MoneyRequest)->getMorphClass();

        MoneyRequestRecords::query()
            ->orderBy('id')
            ->chunkById($chunkSize, function ($records) use (
                $dryRun,
                $subjectType,
                &$imported,
                &$alreadyImported,
                &$unsupportedActions,
            ): void {
                $existingIds = ChangeLog::query()
                    ->whereIn('details->legacy_record_id', $records->modelKeys())
                    ->pluck('details')
                    ->map(fn (array|string $details) => is_array($details) ? $details : json_decode($details, true, flags: JSON_THROW_ON_ERROR))
                    ->pluck('legacy_record_id')
                    ->all();

                foreach ($records as $record) {
                    if (\in_array($record->id, $existingIds, true)) {
                        $alreadyImported++;

                        continue;
                    }

                    $event = match ($record->action) {
                        MoneyRequestRecords::CHANGE_STATUS => ChangeLogEvent::StatusChanged,
                        MoneyRequestRecords::EDITED        => ChangeLogEvent::Edited,
                        default                            => null,
                    };

                    if ($event === null) {
                        $this->warn("Skipping legacy record {$record->id}: unsupported action [{$record->action}].");
                        $unsupportedActions++;

                        continue;
                    }

                    if ($dryRun) {
                        $imported++;

                        continue;
                    }

                    $registeredAt = $record->registered_at ?? $record->created_at ?? now();
                    $changeLog    = new ChangeLog([
                        'user_id'      => $record->user_id,
                        'subject_type' => $subjectType,
                        'subject_id'   => $record->money_request_id,
                        'event'        => $event,
                        'details'      => [
                            'version'          => 1,
                            'legacy_record_id' => $record->id,
                            'legacy'           => (string) ($record->details ?? ''),
                        ],
                    ]);
                    $changeLog->created_at = $registeredAt;
                    $changeLog->updated_at = $registeredAt;
                    $changeLog->save();

                    $imported++;
                }
            });

        $verb = $dryRun ? 'Would import' : 'Imported';
        $this->info("{$verb} {$imported} legacy record(s); skipped {$alreadyImported} already imported record(s).");

        if ($unsupportedActions > 0) {
            $this->error("{$unsupportedActions} record(s) had unsupported actions and were not imported.");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
