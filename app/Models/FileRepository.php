<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;

class FileRepository
{
    public function __construct(
        private Explorer $database,
        private string $uploadsDir
    )
    {
    }
}