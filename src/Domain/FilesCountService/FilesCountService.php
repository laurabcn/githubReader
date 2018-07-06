<?php

namespace App\Domain\FilesCountService;

use App\Domain\FilesTreeReader\File;
use App\Domain\FilesTreeReader\WordOccurrence;

class FilesCountService
{
    /**
     * @param File[]
     * @return WordOccurrence[]
     */
    public static function countOccurrences(array $files): array
    {
        $words = array_reduce($files, function (array $carry, File $file) {
            preg_match_all('/[A-Z]+[a-z0-9]+/', $file->name(), $matches);
            if (empty($matches[0])) {
                return $carry;
            }

            return array_merge($carry, array_values($matches[0]));
        }, []);

        $occurrences = array_reduce($words, function (array $carry, string $word) {
            if (!isset($carry[$word])) {
                $carry[$word] = ['count' => 0 , 'word' => $word];
            };
            $carry[$word]['count']++;

            return $carry;
        }, []);

        return array_map(function(array $occurrence){
            return new WordOccurrence($occurrence['word'], $occurrence['count']);
        }, $occurrences);
    }
}