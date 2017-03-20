<?php

namespace App\Model\Program;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;


/**
 * Entita místnost.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 * @ORM\Entity(repositoryClass="RoomRepository")
 * @ORM\Table(name="room")
 */
class Room
{
    use Identifier;

    /**
     * Název místnosti.
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    protected $name;

    /**
     * Programy v místnosti.
     * @ORM\OneToMany(targetEntity="Program", mappedBy="room", cascade={"persist"})
     * @var ArrayCollection
     */
    protected $programs;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPrograms()
    {
        return $this->programs;
    }

    /**
     * @param mixed $programs
     */
    public function setPrograms($programs)
    {
        $this->programs = $programs;
    }
}