<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Http\FileUpload;
use Nette\InvalidStateException;
use Nette\IOException;
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

    public function deleteFolder(string $folder): void
    {
        FileSystem::delete($this->uploadsDir . DIRECTORY_SEPARATOR . $folder);
    }

    /**
     * Returns a path to a file from $folder within default $uploadsDir or null
     * if the folder is empty or does not exist.
     */
    public function findFile(string $folder): ?string
    {
        try {
            $files = Finder::find()
                ->in($this->uploadsDir . DIRECTORY_SEPARATOR . $folder)
                ->collect();

            if ($files) {
                return $files[0]->getPathname();
            }
            return null;
        } catch (InvalidStateException) {
            return null;
        }
    }
}