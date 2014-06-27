<?php

namespace Nonlux\BitApp\Console\Command;

use Nonlux\BitApp\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class BitrixClearAllCommand extends Command
{


    protected $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName("bitrix:clear:all");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Clear ");
        $filesystem = new Filesystem();
        $delete_dir = $this->basePath;

        $output->writeln("Clear $delete_dir");
        $filesystem->remove($delete_dir);
        $output->writeln("Done...");
    }


}
