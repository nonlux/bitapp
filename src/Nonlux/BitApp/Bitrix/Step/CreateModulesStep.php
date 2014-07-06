<?php
namespace Nonlux\BitApp\Bitrix\Step;

use CWizardStep;
use Nonlux\BitApp\Bitrix\Main\Option;

class CreateModulesStep extends \CreateModulesStep
{
    public function SendResponse($js)
    {
        if (preg_match('/window\.ajaxForm\.Post\(\'([^\']+)\',\s+\'([^\']+)\'/', $js, $matches)) {
            $this->setNext($matches[1], $matches[2]);
        } else {
            $this->setNext('__finish');
        }
    }

    public function setNext($nextStep, $nextStage = null)
    {
        $this->GetWizard()->SetVar('nextStep', $nextStep);
        $this->GetWizard()->SetVar('nextStepStage', $nextStage);
        return;
    }

    public function SetError($strError, $id = false)
    {
        throw new \Exception($strError);
    }

    public function ShowStep()
    {

    }
}
