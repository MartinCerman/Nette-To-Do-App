<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Http\FileUpload;
use Nette\InvalidStateException;
use Nette\IOException;
use Nette\Utils\FileInfo;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class UploadsRepository
{
    public function __construct(
        private string $uploadsDir
    )
    {
    }

    /**
     * Adds a file to a new specified folder within default $uploadsDir folder
     * if the folder already exists, its content is deleted.
     */
    public function addFile(string $folder, FileUpload $file): void
    {
        $taskFolder = $this->uploadsDir . DIRECTORY_SEPARATOR . $folder;

        FileSystem::delete($taskFolder);

        FileSystem::copy($file->temporaryFile, $taskFolder . DIRECTORY_SEPARATOR . $file->name);
    }

    /**
     * Removes $folder within user's uploads directory .
     * @param string $folder Must not be empty and must contain alphanumeric
     * characters only.
     */
    public function deleteFolder(string $folder, int $userId): void
    {
        if(!ctype_alnum($folder)){
            throw new \InvalidArgumentException(
                "Error resolving path '$folder'.");
        }
        FileSystem::delete($this->uploadsDir . DIRECTORY_SEPARATOR . $userId . DIRECTORY_SEPARATOR . $folder);
    }

    /**
     * Returns FileInfo of a file from $folder within default $uploadsDir or null
     * if the folder is empty or does not exist.
     */
    public function findFile(string $folder): ?FileInfo
    {
        try {
            $files = Finder::find()
                ->in($this->uploadsDir . DIRECTORY_SEPARATOR . $folder)
                ->collect();

            if ($files) {
                return $files[0];
            }
            return null;
        } catch (InvalidStateException) {
            return null;
        }
    }
}