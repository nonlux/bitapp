<?php
namespace Nonlux\BitApp\Console;

use Nonlux\BitApp\Console\Command\BitrixAssetsDump;
use Nonlux\BitApp\Console\Command\BitrixClearAllCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpKavsmCommand;
use Nonlux\BitApp\Console\Command\BitrixInstallCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpStandardCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;

class Application extends BaseApplication
{
    const DEFAULT_DUMP_PATH = "/web";
    const APP_NAME = "Bitapp";
    const VERSION = "DEV";

    public function __construct($vendorPath)
    {
        parent::__construct(Application::APP_NAME, Application::VERSION);
    }

    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new BitrixClearAllCommand($this->getBasePath() . Application::DEFAULT_DUMP_PATH),
            )
        );
    }

    public function getBasePath()
    {
        return getcwd();
    }
}
