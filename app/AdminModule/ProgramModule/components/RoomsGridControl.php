<?php

namespace App\AdminModule\ProgramModule\Components;

use App\Model\Program\Room;
use App\Model\Program\RoomRepository;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Ublaboo\DataGrid\DataGrid;


class RoomsGridControl extends Control
{
    /** @var Translator */
    private $translator;

    /** @var RoomRepository */
    private $roomRepository;


    /**
     * RoomsGridControl constructor.
     * @param Translator $translator
     * @param RoomRepository $roomRepository
     */
    public function __construct(Translator $translator, RoomRepository $roomRepository)
    {
        parent::__construct();

        $this->translator = $translator;
        $this->roomRepository = $roomRepository;
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/templates/rooms_grid.latte');
    }

    public function createComponentRoomsGrid($name)
    {
        $grid = new DataGrid($this, $name);
        $grid->setTranslator($this->translator);
        $grid->setDataSource($this->roomRepository->createQueryBuilder('r'));
        $grid->setDefaultSort(['name' => 'ASC']);
        $grid->setPagination(false);

        $grid->addColumnText('name', 'admin.program.rooms_name');

        $grid->addInlineAdd()->onControlAdd[] = function ($container) {
            $container->addText('name', '')
                ->addRule(Form::FILLED, 'admin.program.rooms_name_empty')
                ->addRule(Form::IS_NOT_IN, 'admin.program.rooms_name_exists', $this->roomRepository->findAllNames());
        };
        $grid->getInlineAdd()->onSubmit[] = [$this, 'add'];

        $grid->addInlineEdit()->onControlAdd[] = function ($container) {
            $container->addText('name', '')
                ->addRule(Form::FILLED, 'admin.program.rooms_name_empty');
        };
        $grid->getInlineEdit()->onSetDefaults[] = function ($container, $item) {
            $container['name']
                ->addRule(Form::IS_NOT_IN, 'admin.program.rooms_name_exists', $this->roomRepository->findOthersNames($item->getId()));

            $container->setDefaults([
                'name' => $item->getName()
            ]);
        };
        $grid->getInlineEdit()->onSubmit[] = [$this, 'edit'];

        $grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('admin.common.delete')
            ->setClass('btn btn-xs btn-danger')
            ->addAttributes([
                'data-toggle' => 'confirmation',
                'data-content' => $this->translator->translate('admin.program.rooms_delete_confirm')
            ]);
    }

    public function add($values)
    {
        $room = new Room();

        $room->setName($values['name']);

        $this->roomRepository->save($room);

        $p = $this->getPresenter();
        $p->flashMessage('admin.program.rooms_saved', 'success');

        $this->redirect('this');
    }

    public function edit($id, $values)
    {
        $room = $this->roomRepository->findById($id);

        $room->setName($values['name']);

        $this->roomRepository->save($room);

        $p = $this->getPresenter();
        $p->flashMessage('admin.program.rooms_saved', 'success');

        $this->redirect('this');
    }

    public function handleDelete($id)
    {
        $room = $this->roomRepository->findById($id);
        $this->roomRepository->remove($room);

        $this->getPresenter()->flashMessage('admin.program.rooms_deleted', 'success');

        $this->redirect('this');
    }
}