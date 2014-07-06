<?php

namespace Nonlux\BitApp\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;

class BitrixDumpStandardCommand extends AbstractDumpCommand
{
    protected $projectPath;
    protected $sourcePath;

    public function __construct($sourcePath, $projectPath)
    {
        $this->projectPath=$projectPath;
        $this->sourcePath=$sourcePath;
        parent::__construct(null);
    }
    protected function configure()
    {
        $this->setName("bitrix:dump:standard");
        $this->setDescription("Dump bitrix standard edition in folder");
        $this->addOption("debug", null, InputOption::VALUE_NONE, "dump debug fixtures");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $targetDir = rtrim($this->projectPath, '/\\');
        $originDir = rtrim($this->sourcePath, '/\\');
        $output->writeln("Dump bitrix");

        $this->dumpFiles($originDir, $targetDir);

        $output->writeln("Done...");
    }

}
