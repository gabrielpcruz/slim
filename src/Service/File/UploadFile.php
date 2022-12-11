<?php

namespace App\Service\File;

use Psr\Http\Message\UploadedFileInterface;

class UploadFile
{
    /**
     * @param UploadedFileInterface $uploadedFile
     * @param $destinationPath
     * @return string
     */
    public static function upload(UploadedFileInterface $uploadedFile, $destinationPath): string
    {
        $fullFileName = "";

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            File::createPathIfNotExists($destinationPath);

            $filename = $uploadedFile->getClientFilename();

            $filename = mb_strtolower($filename);

            $filename = str_replace(' ', '_', $filename);

            $fullFileName = "{$destinationPath}/{$filename}";

            var_dump($fullFileName);

            $uploadedFile->moveTo($fullFileName);
        }

        return $fullFileName;
    }
}
