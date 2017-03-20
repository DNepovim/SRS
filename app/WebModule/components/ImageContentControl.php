<?php

namespace App\WebModule\Components;

use Nette\Application\UI\Control;


class ImageContentControl extends Control
{
    public function render($content)
    {
        $template = $this->template;
        $template->setFile(__DIR__ . '/templates/image_content.latte');

        $template->heading = $content->getHeading();
        $template->image = $content->getImage();
        $template->align = $content->getAlign();
        $template->width = $content->getWidth();
        $template->height = $content->getHeight();

        $template->render();
    }
}