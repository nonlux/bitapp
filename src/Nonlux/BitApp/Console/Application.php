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
    const DEFAULT_SOURCE_PATH="/bitrix/bitrixcms-standard";
    const APP_NAME = "Bitapp";
    const VERSION = "DEV";

    protected $vendorPath;
    public function __construct($vendorPath)
    {
        $this->vendorPath=$vendorPath;
        parent::__construct(Application::APP_NAME, Application::VERSION);

    }

    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new BitrixClearAllCommand($this->getProjectPath()),
                new BitrixDumpStandardCommand($this->getSourcePath(),$this->getProjectPath()),
            )
        );
    }

    public function getBasePath()
    {
        return getcwd();
    }

    public function getProjectPath(){
        return $this->getBasePath() . Application::DEFAULT_DUMP_PATH;
    }
    public function getSourcePath(){
             return $this->vendorPath.Application::DEFAULT_SOURCE_PATH;
    }
}
