<?php

namespace App\Domain\FilesTreeReader;

final class WordOccurrence
{
    /** @var string */
    private $word;
    /** @var int */
    private $occurrences;

    public function __construct(string $word, int $occurrences)
    {
        $this->word = $word;
        $this->occurrences = $occurrences;
    }

    public function name(): string
    {
        return $this->word;
    }

    public function occurrences(): int
    {
        return $this->occurrences;
    }
}