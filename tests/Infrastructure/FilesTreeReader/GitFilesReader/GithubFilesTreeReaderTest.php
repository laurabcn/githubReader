<?php

namespace App\Infrastructure\FilesTreeReader\GitFilesReader;

use App\Domain\FilesTreeReader\File;
use App\Tests\Tools\DummyHttpClient;
use PHPUnit\Framework\TestCase;

class GithubFilesTreeReaderTest extends TestCase
{
    /** @var DummyHttpClient */
    private $client;
    /** @var GithubFilesTreeReader $sut */
    private $sut;

    protected function setUp()
    {
        $this->client = new DummyHttpClient();
        $getFilesFromGit = new GetFilesFromGit($this->client);
        $this->sut = new GithubFilesTreeReader($getFilesFromGit);
    }

    public function testRetrieveFiles()
    {
        $shaResponse = [
            ["object" => ["sha" => 'sha-value']]
        ];

        $responseLevel1 = [
            'tree' => [
                ['path' => 'file.rb', "type" => "blob", 'url' => 'any-url'],
                ['path' => 'subdir', "type" => "tree", 'url' => 'any-subfolder-url'],
                ['path' => 'exec_file', "type" => "blob", 'url' => 'another-url'],
            ]
        ];

        $responseLevel2 = [
            'tree' => [
                ['path' => 'file2.rb', "type" => "blob", 'url' => 'any-url'],
                ['path' => 'another-subdir', "type" => "tree", 'url' => 'another-subfolder-url'],
                ['path' => 'exec_file', "type" => "blob", 'url' => 'another-url'],
            ]
        ];

        $responseLevel3 = [
            'tree' => [
                ['path' => 'file3.rb', "type" => "blob", 'url' => 'any-url'],
                ['path' => 'index.php', "type" => "blob", 'url' => 'any-url'],
            ]
        ];

        $this->client->appendResponse(200, [], json_encode($shaResponse));
        $this->client->appendResponse(200, [], json_encode($responseLevel1));
        $this->client->appendResponse(200, [], json_encode($responseLevel2));
        $this->client->appendResponse(200, [], json_encode($responseLevel3));

        $files = $this->sut->read('project-path');

        $expectedFiles = [
            new File('exec_file'),
            new File('exec_file'),
            new File('file.rb'),
            new File('file2.rb'),
            new File('file3.rb'),
            new File('index.php'),
        ];

        self::assertEquals($expectedFiles, $files);
    }
}
