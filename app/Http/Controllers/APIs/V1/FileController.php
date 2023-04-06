<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\FileError;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Services\Actions\File;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    public function __construct(protected readonly File $action)
    {
    }

    public function uploadFile(FileRequest $request): JsonResponse
    {
        try {
            $fileDetails = $this->action->uploadFile($request);
            return ApiResponse::success($fileDetails);
        } catch (\Exception $exception) {
            return ApiResponse::failed($exception->getMessage());
        }
    }

    public function getFileDetails(string $uuid): JsonResponse
    {
        try {
            $fileDetails = $this->action->fetchFile($uuid);
            return ApiResponse::success($fileDetails);
        } catch (FileError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
    }
}
