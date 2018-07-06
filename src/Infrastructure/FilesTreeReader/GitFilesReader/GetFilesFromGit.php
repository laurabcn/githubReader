<?php

namespace App\Infrastructure\FilesTreeReader\GitFilesReader;

use GuzzleHttp\Client;

class GetFilesFromGit
{
    CONST urlApiGit = 'https://api.github.com/';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute(string $project)
    {
        $sha = $this->getLastCommit($project);

        $rootUrlProject= self::urlApiGit . 'repos/' . $project . '/git/trees/' . $sha;

        return $this->retrieveFiles($rootUrlProject);
    }

    private function getLastCommit(string $project) : string
    {
        $path = self::urlApiGit . 'repos/' . $project . '/git/refs';

        $response = $this->getFromGit($path);

        return $response[0]['object']['sha'];
    }

    private function retrieveFiles(String $path): array
    {
        $response = $this->getFromGit($path);

        $files = [];
        foreach ($response['tree'] as $key => $value) {
            $type = $value['type'];

            if ($type === 'tree') {
                $files = array_merge($files, $this->retrieveFiles($value['url']));
            } else {
                $files[] = $value['path'];
            }
        }

        return $files;
    }

    protected function getFromGit(string $path){
         $response =  $this->client->request('GET', $path, [
            'auth' => ['laurabcn', 'ea721af0fc2fcb62dd1d8d6e195790b8f1f0c84c']
        ]);

        return json_decode($response->getBody(), true);
    }
}