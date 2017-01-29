<?php

namespace App\AdminModule\Forms;

use App\Model\CMS\PageRepository;
use Nette\Application\UI\Form;

class SystemConfigurationFormFactory
{
    /**
     * @var BaseFormFactory
     */
    private $baseFormFactory;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    public function __construct(BaseFormFactory $baseFormFactory, PageRepository $pageRepository)
    {
        $this->baseFormFactory = $baseFormFactory;
        $this->pageRepository = $pageRepository;
    }

    public function create()
    {
        $form = $this->baseFormFactory->create();

        $renderer = $form->getRenderer();
        $renderer->wrappers['control']['container'] = 'div class="col-sm-7 col-xs-7"';
        $renderer->wrappers['label']['container'] = 'div class="col-sm-5 col-xs-5 control-label"';

        $form->addText('footer', 'admin.configuration.footer');

        $pagesChoices = $this->preparePagesChoices();

        $form->addSelect('redirectAfterLogin', 'admin.configuration.redirect_after_login', $pagesChoices)
            ->addRule(Form::FILLED, 'admin.configuration.redirect_after_login_empty');

        $form->addCheckbox('displayUsersRoles', 'admin.configuration.display_users_roles');

        $form->addSubmit('submit', 'admin.common.save');

        return $form;
    }

    private function preparePagesChoices() {
        $choices = [];
        foreach ($this->pageRepository->findPublishedPagesOrderedBySlug() as $page)
            $choices[$page->getSlug()] = $page->getName();
        return $choices;
    }
}
