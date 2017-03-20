<?php

namespace App\AdminModule\ConfigurationModule\Presenters;

use App\AdminModule\ConfigurationModule\Forms\WebForm;
use App\Model\Settings\Settings;
use Nette\Application\UI\Form;


class WebPresenter extends ConfigurationBasePresenter
{
    /**
     * @var WebForm
     * @inject
     */
    public $webFormFactory;


    public function renderDefault()
    {
        $this->template->logo = $this->settingsRepository->getValue(Settings::LOGO);
    }

    protected function createComponentSettingsForm()
    {
        $form = $this->webFormFactory->create();

        $form->onSuccess[] = function (Form $form, \stdClass $values) {
            $this->flashMessage('admin.configuration.configuration_saved', 'success');

            $this->redirect('this');
        };

        return $form;
    }
}