<?php

namespace Nonlux\BitApp\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class BitrixDumpFixtureCommand extends AbstractDumpCommand
{
    protected $projectPath;
    protected $fixturesPath;

    public function __construct($projectPath, $fixturesPath)
    {
        $this->projectPath = $projectPath;
        $this->fixturesPath = $fixturesPath;
        parent::__construct(null);
    }


    protected function configure()
    {
        $this->setName("bitrix:dump:fixture");
        $this->setDescription("Dump fixture files in folder");
        $this->addArgument("dir", InputArgument::REQUIRED, "path of fixture");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $targetDir = rtrim($this->projectPath, '/\\');
        $dir = $input->getArgument('dir');
        $originDir = rtrim($this->fixturesPath . "/$dir", '/\\');
        if (!file_exists($originDir)) {
            throw new FileNotFoundException($originDir);
        }
        $output->writeln("Dump $dir fixtures");
        $this->dumpFiles($originDir, $targetDir);
        $output->writeln("Done...");
    }


}
