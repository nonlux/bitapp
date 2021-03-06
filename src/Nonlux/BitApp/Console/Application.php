<?php
namespace Nonlux\BitApp\Console;

use Nonlux\BitApp\Console\Command\BitrixAssetsDump;
use Nonlux\BitApp\Console\Command\BitrixClearAllCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpFixtureCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpKavsmCommand;
use Nonlux\BitApp\Console\Command\BitrixInstallCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpStandardCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const DEFAULT_DUMP_PATH = "/web";
    const DEFAULT_SOURCE_PATH="/bitrix/bitrixcms-standard";
    const DEFAULT_FIXTURES_PATH = "/fixtures";
    const APP_NAME = "Bitapp";
    const VERSION = "DEV";

    protected $vendorPath;
    protected $config;
    public function __construct($vendorPath)
    {
        $this->vendorPath=$vendorPath;
        $this->config = Yaml::parse("bitapp.yml");
        parent::__construct(Application::APP_NAME, Application::VERSION);

    }

    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            array(
                new BitrixClearAllCommand($this->getProjectPath()),
                new BitrixDumpStandardCommand($this->getSourcePath(),$this->getProjectPath()),
                new BitrixDumpFixtureCommand($this->getProjectPath(), $this->getFixturesPath()),
                new BitrixInstallCommand($this->getProjectPath(), $this->config['install'] ),
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
        return $this->vendorPath . Application::DEFAULT_SOURCE_PATH;
    }

    public function getFixturesPath()
    {
        return $this->getBasePath() . Application::DEFAULT_FIXTURES_PATH;
    }
}
