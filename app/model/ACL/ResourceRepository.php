<?php

declare(strict_types=1);

namespace App\Model\ACL;

use Kdyby\Doctrine\EntityRepository;
use function array_map;

/**
 * Třída spravující prostředky.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 */
class ResourceRepository extends EntityRepository
{
    /**
     * Vrací názvy všech prostředků.
     * @return string[]
     */
    public function findAllNames() : array
    {
        $names = $this->createQueryBuilder('r')
            ->select('r.name')
            ->getQuery()
            ->getScalarResult();
        return array_map('current', $names);
    }
}
