<?php

namespace App\AdminModule\Forms;

use Nette\Application\UI\Form;

class PaymentConfigurationFormFactory
{
    /**
     * @var BaseFormFactory
     */
    private $baseFormFactory;

    public function __construct(BaseFormFactory $baseFormFactory)
    {
        $this->baseFormFactory = $baseFormFactory;
    }

    public function create()
    {
        $form = $this->baseFormFactory->create();

        $renderer = $form->getRenderer();
        $renderer->wrappers['control']['container'] = 'div class="col-sm-7 col-xs-7"';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-5 col-xs-5 control-label"';

        $form->addText('accountNumber', 'admin.configuration.account_number')
            ->addRule(Form::FILLED, 'admin.configuration.account_number_empty')
            ->addRule(Form::PATTERN, 'admin.configuration.account_number_format', '^(\d{1,6}-|)\d{2,10}\/\d{4}$');

        $form->addText('variableSymbolCode', 'admin.configuration.variable_symbol_code', 2)
            ->addRule(Form::FILLED, 'admin.configuration.variable_symbol_code_empty')
            ->addRule(Form::PATTERN, 'admin.configuration.variable_symbol_code_format', '^\d{2}$');

        $form->addSubmit('submit', 'admin.common.save');

        return $form;
    }
}
