<?php
declare(strict_types=1);

namespace App\WebModule\Components;

use App\Model\Settings\Place\PlacePointRepository;
use App\Model\Settings\Settings;
use App\Model\Settings\SettingsRepository;
use Nette\Application\UI\Control;


/**
 * Komponenta s místem.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 */
class PlaceContentControl extends Control
{
    /** @var SettingsRepository */
    private $settingsRepository;

    /** @var PlacePointRepository */
    private $placePointRepository;


    /**
     * PlaceContentControl constructor.
     * @param SettingsRepository $settingsRepository
     * @param PlacePointRepository $placePointRepository
     */
    public function __construct(SettingsRepository $settingsRepository, PlacePointRepository $placePointRepository)
    {
        parent::__construct();

        $this->settingsRepository = $settingsRepository;
        $this->placePointRepository = $placePointRepository;
    }

    /**
     * @param $content
     * @throws \App\Model\Settings\SettingsException
     * @throws \Throwable
     */
    public function render($content)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/templates/place_content.latte');

        $template->heading = $content->getHeading();
        $template->description = $this->settingsRepository->getValue(Settings::PLACE_DESCRIPTION);
        $template->points = $this->placePointRepository->findAll();

        $template->render();
    }
}
