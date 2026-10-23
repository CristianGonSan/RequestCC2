<?php

namespace App\Http\Controllers\Media;

use App\Models\CustomMedia;
use Illuminate\Routing\Controller;

use function readfile;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    private const ACCESS_PERMISSIONS = [
        'manage_requests',
        'manage_accounting',
        'manage_incomes',
        'manage_material_requests',
        'fulfill_material_requests',
    ];

    public function show(int $id): StreamedResponse
    {
        $media = CustomMedia::findOrFail($id);
        $this->authorizeMediaAccess($media);

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
        $this->authorizeMediaAccess($media);

        return response()->download($media->getPath(), $media->file_name);
    }

    private function authorizeMediaAccess(CustomMedia $media): void
    {
        $user = auth()->user();

        abort_unless($user !== null, 403);

        if ($user->hasAnyPermission(self::ACCESS_PERMISSIONS)) {
            return;
        }

        $model = $media->model;

        abort_unless(
            $model !== null && (int) $model->getAttribute('user_id') === (int) $user->getKey(),
            403
        );
    }
}
