<?php

namespace App\AdminModule\CMSModule\Components;

use App\Model\CMS\NewsRepository;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Ublaboo\DataGrid\DataGrid;


class NewsGridControl extends Control
{
    /** @var Translator */
    private $translator;

    /** @var NewsRepository */
    private $newsRepository;


    public function __construct(Translator $translator, NewsRepository $newsRepository)
    {
        parent::__construct();

        $this->translator = $translator;
        $this->newsRepository = $newsRepository;
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/templates/news_grid.latte');
    }

    public function createComponentNewsGrid($name)
    {
        $grid = new DataGrid($this, $name);
        $grid->setTemplateFile(__DIR__ . '/templates/news_grid_template.latte');
        $grid->setTranslator($this->translator);
        $grid->setDataSource($this->newsRepository->createQueryBuilder('n'));
        $grid->setDefaultSort(['published' => 'DESC']);
        $grid->setPagination(false);

        $grid->addColumnDateTime('published', 'admin.cms.news_published')
            ->setFormat('j. n. Y H:i');

        $grid->addColumnText('text', 'admin.cms.news_text');


        $grid->addToolbarButton('News:add')
            ->setIcon('plus')
            ->setTitle('admin.common.add');

        $grid->addAction('edit', 'admin.common.edit', 'News:edit');

        $grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('admin.common.delete')
            ->setClass('btn btn-xs btn-danger')
            ->addAttributes([
                'data-toggle' => 'confirmation',
                'data-content' => $this->translator->translate('admin.cms.news_delete_confirm')
            ]);
    }

    public function handleDelete($id)
    {
        $news = $this->newsRepository->findById($id);
        $this->newsRepository->remove($news);

        $this->getPresenter()->flashMessage('admin.cms.news_deleted', 'success');

        $this->redirect('this');
    }
}