<?php

namespace App\Service\File;

use Psr\Http\Message\UploadedFileInterface;

class UploadFile
{
    /**
     * @param UploadedFileInterface $uploadedFile
     * @param string $destinationPath
     * @return string
     */
    public static function upload(UploadedFileInterface $uploadedFile, string $destinationPath): string
    {
        $fullFileName = "";

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            File::createPathIfNotExists($destinationPath);

            $filename = $uploadedFile->getClientFilename() ?? '';

            $filename = mb_strtolower($filename);

            $filename = str_replace(' ', '_', $filename);

            $fullFileName = "{$destinationPath}/{$filename}";

            $uploadedFile->moveTo($fullFileName);
        }

        return $fullFileName;
    }
}
