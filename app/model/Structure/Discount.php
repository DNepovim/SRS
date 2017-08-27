<?php

namespace App\Model\Structure;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;


/**
 * Entita sleva.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 * @ORM\Entity(repositoryClass="DiscountRepository")
 * @ORM\Table(name="discount")
 */
class Discount
{
    use Identifier;

    /**
     * Podmínka - přihlášené podakce.
     * @ORM\ManyToMany(targetEntity="\App\Model\Structure\Subevent")
     * @var ArrayCollection
     */
    protected $conditionSubevents;

    /**
     * Podmínka - operátor.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $operator;

    /**
     * Sleva.
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $discount;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getConditionSubevents()
    {
        return $this->conditionSubevents;
    }

    /**
     * @param ArrayCollection $conditionSubevents
     */
    public function setConditionSubevents($conditionSubevents)
    {
        $this->conditionSubevents = $conditionSubevents;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return int
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param int $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }
}
