<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

class UploadsRepository
{
    public function __construct(
        private Explorer $database,
        private string   $uploadsDir
    )
    {
    }

    /**
     * Adds a file to a new specified folder within default $uploadsDir folder
     * if the folder already exists, its content is deleted.
     */
    public function addFile(string $folder, string $tempPath): void
    {
        $taskFolder = $this->uploadsDir . DIRECTORY_SEPARATOR . $folder;

        // if data/uploads/$taskId folder exists delete it with its content
        FileSystem::delete($taskFolder);

        // move the file from tempPath to $taskId folder
        $tempPathArray = explode(DIRECTORY_SEPARATOR, $tempPath);
        $fileName = end($tempPathArray);
        FileSystem::copy($tempPath, $taskFolder . DIRECTORY_SEPARATOR . $fileName);
    }
}