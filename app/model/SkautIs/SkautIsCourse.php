<?php

declare(strict_types=1);

namespace App\Model\SkautIs;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Entita skautIS kurz.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 * @ORM\Entity(repositoryClass="SkautIsCourseRepository")
 * @ORM\Table(name="skaut_is_course")
 */
class SkautIsCourse
{
    use Identifier;

    /**
     * SkautIS id kurzu.
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $skautIsCourseId;

    /**
     * Název kurzu.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;


    public function getId() : int
    {
        return $this->id;
    }

    public function getSkautIsCourseId() : int
    {
        return $this->skautIsCourseId;
    }

    public function setSkautIsCourseId(int $skautIsCourseId) : void
    {
        $this->skautIsCourseId = $skautIsCourseId;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }
}
