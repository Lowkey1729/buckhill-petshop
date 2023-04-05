<?php

namespace App\Services\Actions;

use App\Exceptions\FileError;
use App\Models\File as FileModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class File
{
    /**
     * @param Request $request
     * @return FileModel
     */
    public function uploadFile(Request $request): FileModel
    {
        return FileModel::query()->create(
            $this->fileDetails($request)
        );
    }

    /**
     * @param string $uuid
     * @return FileModel
     * @throws FileError
     */
    public function fetchFile(string $uuid): FileModel
    {
        $file = FileModel::whereUuid($uuid)->first();
        if (!$file) {
            throw new FileError('File not found.');
        }
        return $file;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function fileDetails(Request $request): array
    {
        /** @var UploadedFile $file */
        $file = $request->file('file');
        $filePath = Storage::putFile(
            'pet-shop',
            $file
        );

        return [
            'name' => $file->getFilename(),
            'size' => $this->formatBytes($file->getSize(), 2),
            'path' => $filePath,
            'type' => $file->getClientMimeType()
        ];
    }

    /**
     * @param int $size
     * @param int $precision
     * @return string
     */
    protected function formatBytes(int $size, int $precision = 0): string
    {
        $unit = ['Byte', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];

        for ($i = 0; $size >= 1024 && $i < count($unit) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $unit[$i];
    }
}
