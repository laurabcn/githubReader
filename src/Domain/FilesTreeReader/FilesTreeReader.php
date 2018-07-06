<?php

namespace App\Domain\FilesTreeReader;

interface FilesTreeReader
{
    /** @return File[] */
    public function read(string $path) : array;
}
