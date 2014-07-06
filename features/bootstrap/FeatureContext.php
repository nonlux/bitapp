<?php
define('BEHAT_ERROR_REPORTING', E_ERROR | E_WARNING | E_PARSE);

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Nonlux\BitApp\Console\Command\BitrixClearAllCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpStandardCommand;
use Nonlux\BitApp\Console\Command\BitrixDumpFixtureCommand;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\ConsoleOutput;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    protected $testPath;
    protected $testProjectPath;

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->testPath = __DIR__ . "/../../test_data";
        $this->testProjectPath = $this->testPath . "/project";
        $this->bitrixSourcePath = __DIR__ . "/../../vendor/bitrix/bitrixcms-standard";
    }


    /**
     * @Given /^Folder "([^"]*)" exist$/
     */
    public function folderExist($dir)
    {
        $realPath = $this->testPath . "/$dir";
        if (!file_exists($realPath)) {
            mkdir($realPath);
        }
    }

    /**
     * @Given /^File "([^"]*)" exist in test project$/
     */
    public function fileExistInTestProject($fileName)
    {
        $realPath = $this->testProjectPath . "/$fileName";
        if (!file_exists($realPath)) {
            file_put_contents($realPath, "test");
        }
    }

    /**
     * @When /^I execute "([^"]*)"$/
     */
    public function iExecute($command)
    {
        $this->iExecuteWith($command, '');
    }

    /**
     * @Then /^File "([^"]*)" shound not exist in test project$/
     */
    public function fileShoundNotExistInTestProject($fileName)
    {
        PHPUnit_Framework_Assert::assertFileNotExists($this->testProjectPath . "/$fileName");
    }

    /**
     * @Then /^File "([^"]*)" in test project should have "([^"]*)" md5$/
     */
    public function fileInTestProjectShouldHaveMd($fileName, $md5)
    {
        PHPUnit_Framework_Assert::assertEquals($md5, md5_file($this->testProjectPath . "/$fileName"));
    }

    protected function createBitrixClearAllCommand()
    {
        return new BitrixClearAllCommand($this->testProjectPath);
    }

    protected function createBitrixDumpStandardCommand()
    {
        return new BitrixDumpStandardCommand($this->bitrixSourcePath, $this->testProjectPath);
    }

    protected function createBitrixDumpFixtureCommand()
    {
        return new BitrixDumpFixtureCommand($this->testProjectPath, $this->testPath . "/fixture");
    }

    protected function createBitrixInstallCommand()
    {
        return new \Nonlux\BitApp\Console\Command\BitrixInstallCommand($this->testProjectPath);
    }

    /**
     * @When /^I execute "([^"]*)" with "([^"]*)"$/
     */
    public function iExecuteWith($command, $data = '')
    {
        $commandClass = array(
            'bitrix:clear:all' => 'BitrixClearAllCommand',
            'bitrix:dump:standard' => 'BitrixDumpStandardCommand',
            'bitrix:dump:fixture' => 'BitrixDumpFixtureCommand',
            'bitrix:install' => 'BitrixInstallCommand',
        );
        if (!array_key_exists($command, $commandClass)) {
            throw new \Exception(sprintf("Command %s not support", $command));
        }


        $output = new NullOutput();
        if ($command === 'bitrix:install') {
            $output = new ConsoleOutput();
        }
        $realCommand = $this->{"create" . $commandClass[$command]}();
        $realCommand->run(new StringInput($data), $output);
    }

}