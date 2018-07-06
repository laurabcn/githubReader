<?php

namespace App\Infrastructure\FilesTreeReader\GitFilesReader;

use App\Domain\FilesTreeReader\File;
use App\Domain\FilesTreeReader\FilesTreeReader;

class GithubFilesTreeReader implements FilesTreeReader
{

    /** @var GetFilesFromGit $filesFromGit */
    private $getFilesFromGit;

    public function __construct(GetFilesFromGit $getFilesFromGit)
    {
        $this->getFilesFromGit = $getFilesFromGit;
    }

    /** @return File[] */
    public function read(string $path): array
    {
        $files = $this->getFilesFromGit->execute($path);

        usort($files, function (string $fileLeft, string $fileRight) {
            return $fileLeft <=> $fileRight;
        });

        return array_map(function (string $file) {
            return new File($file);
        }, $files);
    }
}