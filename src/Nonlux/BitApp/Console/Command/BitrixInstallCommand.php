<?php


namespace Nonlux\BitApp\Console\Command;

use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;
use Nonlux\BitApp\Bitrix\Main\Option;
use Nonlux\BitApp\Bitrix\NewCreateModuleStep;
use Nonlux\BitApp\Bitrix\Step\CreateAdminStep;
use Nonlux\BitApp\Bitrix\Step\CreateDBStep;
use Nonlux\BitApp\Bitrix\Step\CreateModulesStep;
use Nonlux\BitApp\Bitrix\Step\FinishStep;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class BitrixInstallCommand extends Command
{

    protected $projectPath;

    public function __construct($projectPath)
    {
        $this->projectPath = $projectPath;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName("bitrix:install");
        $this->setDescription("install bitrix");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Install bitrix...");
        global $DB, $DBType, $DBHost, $DBLogin, $DBPassword, $DBName, $DBDebug, $DBDebugToFile, $APPLICATION, $USER, $arWizardConfig, $MESS;
        $bitrixRoot = $this->projectPath;
        $_SERVER["DOCUMENT_ROOT"] = $bitrixRoot;
        $_SERVER["REQUEST_URI"] = "/index.php";
        $_SERVER["QUERY_STRING"] = "";
        define("B_PROLOG_INCLUDED", true);
        ob_start();
        require_once("$bitrixRoot/bitrix/modules/main/install/wizard/wizard.php");
        ob_end_clean();
        var_dump(file_get_contents("$bitrixRoot/bitrix/modules/main/admin/define.php"));
        $output->writeln("Step 1. Create database:");
        $wizard = new \CWizardBase("nonlux.createDb.wizard", null);

        $dbName = time() . "_db";
        $output->writeln("database name: $dbName");
        $data = array(
            "agree_license" => "Y",
            "user" => "root",
            "password" => "111",
            "database" => $dbName,
            "utf8" => "Y",
            "dbType" => "mysql",
            "host" => "localhost",
            "create_user" => "N",
            "create_database" => "Y",
            "root_user" => "root",
            "root_password" => "111",
            'file_access_perms' => '0644',
            'folder_access_perms' => '0755',
            'bitrixRoot' => $bitrixRoot
        );


        foreach ($data as $key => $value) {
            $wizard->SetVar($key, $value);
        }
        $step = new \CreateDBStep();
        $wizard->AddStep($step);
        $step->OnPostForm();
        $output->writeln("Done");

        require_once $bitrixRoot . '/bitrix/php_interface/dbconn.php';

        $output->writeln("Step 2. Install modules:");

        $wizard = new \CWizardBase("nonlux.installModules.wizard", null);
        $data = array(
            "nextStep" => "main",
            "nextStepStage" => "utf8",
            'bitrixRoot' => $bitrixRoot,
            "user" => "root",
            "password" => "111",
            "utf8" => "Y",
        );
        $step = new CreateModulesStep();
        $wizard->AddStep($step);
        foreach ($data as $key => $value) {
            $wizard->SetVar($key, $value);
        }
        do {
            $output->writeln("Install " . $wizard->GetVar("nextStep") . " " . $wizard->GetVar("nextStepStage"));

            $step->OnPostForm();
            if ($wizard->GetVar("nextStep") === 'main' && $wizard->GetVar("nextStepStage") === 'files') {
                $HttpApplication = \Bitrix\Main\HttpApplication::getInstance();
                $HttpApplication->initializeBasicKernel();
                $HttpApplication->getCache()->clearCache(true);
                $GLOBALS['CACHE_MANAGER']->Clean('b_option');
                Option::clearOptions("main");
            }
        } while ($wizard->GetVar('nextStep') != '__finish');

        $output->writeln("Done");

        $USER = new \CUser;
        $policy = $USER->GetSecurityPolicy();
        $output->writeln("Step 3. Create admin:");
        $data = array(
            'email' => "nsa@dsa.ru",
            'login' => 'admin',
            'admin_password_confirm' => '111111',
            'admin_password' => '111111',
            'user_name' => 'qqq',
            "utf8" => "Y",
            'user_surname' => 'qqqq'
        );

        foreach ($data as $key => $value) {
            $wizard->SetVar($key, $value);
        }
        $wizard = new \CWizardBase("nonlux.admin.wizard", null);
        $step = new \CreateAdminStep();
        $wizard->AddStep($step);
        $step->OnPostForm();

        $output->writeln("Done");
        $step = new \FinishStep();
        $step->ShowStep();

    }


}
