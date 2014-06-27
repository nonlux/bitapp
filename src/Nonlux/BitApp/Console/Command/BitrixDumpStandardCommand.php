<?php

namespace Nonlux\BitApp\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;

class BitrixDumpStandardCommand extends Command
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

    /**
     * @param $originDir
     * @param $targetDir
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    protected function dumpFiles($originDir, $targetDir)
    {

        $filesystem = new Filesystem();
        $flags = \FilesystemIterator::SKIP_DOTS;
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($originDir, $flags), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            $target = str_replace($originDir, $targetDir, $file->getPathname());
            if (is_link($file) || is_file($file)) {
                $filesystem->copy($file, $target, true);
            } elseif (is_dir($file)) {
                $filesystem->mkdir($target);
            } else {
                throw new IOException(sprintf('Unable to guess "%s" file type.', $file), 0, null, $file);
            }
        }
    }


}
