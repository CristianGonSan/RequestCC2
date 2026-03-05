<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use App\Models\FileManagement;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShowFileController extends Controller
{
    public function previewFile($fileId): StreamedResponse
    {
        $file = FileManagement::findOrFail($fileId);

        $filePath = storage_path('app/' . $file->file_path);
        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado.');
        }

        $mimeType = $file->mime_type;

        return response()->stream(function () use ($filePath) {
            readfile($filePath);
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]);
    }
}
