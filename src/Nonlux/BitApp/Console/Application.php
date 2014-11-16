<?php
namespace Nonlux\BitApp\Console;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Nonlux\BitApp\Console\Command\BitrixAssetsDump;
use Nonlux\BitApp\Console\Command\BitrixClearAllCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpFixtureCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpKavsmCommand;
use Nonlux\BitApp\Console\Command\BitrixInstallCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpStandardCommand;
use Nonlux\BitApp\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Yaml\Yaml;
use Doctrine\DBAL\Migrations\Tools\Console\Command as DoctrineCommand;
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
            ),
            $this->getMigrationComands()
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

    protected function getMigrationComands(){

        $commands= [
            new DoctrineCommand\DiffCommand(),
                new DoctrineCommand\ExecuteCommand(),
                new DoctrineCommand\GenerateCommand(),
                new DoctrineCommand\MigrateCommand(),
                new DoctrineCommand\StatusCommand(),
                new DoctrineCommand\VersionCommand()
            ];


        return $commands;
    }

    protected function getDefaultHelperSet()
    {
        $helperSet=parent::getDefaultHelperSet();

        $params=[
            "driver"=>"pdo_mysql",
            "user"=>$this->config['install']['user'],
            "password"=>$this->config['install']['user_password'],
            "dbname"=>$this->config['install']['database']
        ];

        $config = Setup::createAnnotationMetadataConfiguration(array('data/Entity'), true);
        $em= EntityManager::create($params, $config);
        $db = $em->getConnection();

        $helperSet->set(  new ConnectionHelper($db));
        $helperSet->set( new EntityManagerHelper($em) );
        return $helperSet;
    }
}
