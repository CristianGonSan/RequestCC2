<?php

namespace App\Http\Controllers\Media;

use App\Models\CustomMedia;

use Illuminate\Routing\Controller;
use function readfile;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(int $id): StreamedResponse
    {
        $media = CustomMedia::findOrFail($id);

        return response()->stream(function () use ($media): void {
            readfile($media->getPath());
        }, 200, [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'inline; filename="'.$media->file_name.'"',
        ]);
    }

    public function download(int $id): BinaryFileResponse
    {
        $media = CustomMedia::findOrFail($id);

        return response()->download($media->getPath(), $media->file_name);
    }
}
