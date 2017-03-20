<?php

namespace App\AdminModule\CMSModule\Presenters;

use App\AdminModule\CMSModule\Components\IFaqGridControlFactory;
use App\AdminModule\CMSModule\Forms\FaqForm;
use App\Model\CMS\FaqRepository;
use Nette\Application\UI\Form;


class FaqPresenter extends CMSBasePresenter
{
    /**
     * @var IFaqGridControlFactory
     * @inject
     */
    public $faqGridControlFactory;

    /**
     * @var FaqForm
     * @inject
     */
    public $faqFormFactory;

    /**
     * @var FaqRepository
     * @inject
     */
    public $faqRepository;


    public function renderEdit($id)
    {
    }

    protected function createComponentFaqGrid()
    {
        return $this->faqGridControlFactory->create();
    }

    protected function createComponentFaqForm()
    {
        $form = $this->faqFormFactory->create($this->getParameter('id'), $this->user->id);

        $form->onSuccess[] = function (Form $form, \stdClass $values) {
            if ($form['cancel']->isSubmittedBy())
                $this->redirect('Faq:default');

            $this->flashMessage('admin.cms.faq_saved', 'success');

            if ($form['submitAndContinue']->isSubmittedBy()) {
                $id = $values['id'] ?: $this->faqRepository->findLastId();
                $this->redirect('Faq:edit', ['id' => $id]);
            } else
                $this->redirect('Faq:default');
        };

        return $form;
    }
}