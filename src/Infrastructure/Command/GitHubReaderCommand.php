<?php

namespace App\Infrastructure\Command;

use App\Domain\FilesCountService\FilesCountService;
use App\Domain\FilesTreeReader\FilesTreeReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitHubReaderCommand extends ContainerAwareCommand
{
    private $reader;

    public function __construct(?string $name = null, FilesTreeReader $reader)
    {
        parent::__construct($name);
        $this->reader = $reader;
    }

    protected function configure()
    {
        $this
            ->setName('statistics:v3')
            ->setDescription('set project resource: like symfony/symfony')
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'Show statistics data'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $input->getArgument('project');

        $files = $this->reader->read($project);
        $occurrences = FilesCountService::countOccurrences($files);

        foreach ($occurrences as $occurrence) {
            $output->writeln($occurrence->name() . ' = ' . $occurrence->occurrences());
        }
    }
}