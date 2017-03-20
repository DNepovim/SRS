<?php

namespace App\Model\Program;

use App\Model\User\User;
use App\Model\User\UserRepository;
use Kdyby\Doctrine\EntityRepository;


/**
 * Třída spravující programové bloky.
 *
 * @author Michal Májský
 * @author Jan Staněk <jan.stanek@skaut.cz>
 */
class BlockRepository extends EntityRepository
{
    /** @var UserRepository */
    private $userRepository;


    /**
     * @param UserRepository $userRepository
     */
    public function injectUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Vrací blok podle id.
     * @param $id
     * @return Block|null
     */
    public function findById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Vrací poslední id.
     * @return int
     */
    public function findLastId()
    {
        return $this->createQueryBuilder('b')
            ->select('MAX(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Vrací názvy všech bloků.
     * @return array
     */
    public function findAllNames()
    {
        $names = $this->createQueryBuilder('b')
            ->select('b.name')
            ->getQuery()
            ->getScalarResult();
        return array_map('current', $names);
    }

    /**
     * Vrací všechny bloky seřazené podle názvu.
     * @return array
     */
    public function findAllOrderedByName()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vrací všechny bloky nezařezené v kategorii, seřazené podle názvu.
     * @return array
     */
    public function findAllUncategorizedOrderedByName()
    {
        return $this->createQueryBuilder('b')
            ->where('b.category IS NULL')
            ->orderBy('b.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vrací názvy ostatních bloků, kromě bloku se zadaným id.
     * @param $id
     * @return array
     */
    public function findOthersNames($id)
    {
        $names = $this->createQueryBuilder('b')
            ->select('b.name')
            ->where('b.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getScalarResult();
        return array_map('current', $names);
    }

    /**
     * Vrací bloky podle textu obsaženého v názvu, seřazené podle názvu.
     * @param $text
     * @param bool $unassignedOnly
     * @return array
     */
    public function findByLikeNameOrderedByName($text, $unassignedOnly = false)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.name LIKE :text')->setParameter('text', '%' . $text . '%');

        if ($unassignedOnly) {
            $qb = $qb->leftJoin('b.programs', 'p')
                ->andWhere('SIZE(b.programs) = 0');
        }

        return $qb->orderBy('b.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vrací názvy bloků, které jsou pro uživatele povinné, ale není na ně přihlášený.
     * @param User $user
     * @return array
     */
    public function findUserMandatoryNotRegisteredNames($user)
    {
        $registerableCategoriesIds = $this->userRepository->findRegisterableCategoriesIdsByUser($user);

        $usersBlocks = $this->createQueryBuilder('b')
            ->select('b')
            ->leftJoin('b.programs', 'p')
            ->leftJoin('p.attendees', 'u')
            ->where('u.id = :uid')
            ->setParameter('uid', $user->getId())
            ->getQuery()
            ->getResult();

        $qb = $this->createQueryBuilder('b')
            ->select('b.name')
            ->leftJoin('b.category', 'c')
            ->where($this->createQueryBuilder()->expr()->orX(
                'c.id IN (:ids)',
                'b.category IS NULL'
            ))
            ->andWhere('b.mandatory > 0')
            ->setParameter('ids', $registerableCategoriesIds);

        if (!empty($usersBlocks)) {
            $qb = $qb
                ->andWhere('b NOT IN (:usersBlocks)')
                ->setParameter('usersBlocks', $usersBlocks);
        }

        $names = $qb
            ->getQuery()
            ->getScalarResult();

        return array_map('current', $names);
    }

    /**
     * Vrací id bloků.
     * @param $blocks
     * @return array
     */
    public function findBlocksIds($blocks)
    {
        return array_map(function ($o) {
            return $o->getId();
        }, $blocks->toArray());
    }

    /**
     * Uloží blok.
     * @param Block $block
     */
    public function save(Block $block)
    {
        $this->_em->persist($block);
        $this->_em->flush();
    }

    /**
     * Odstraní blok.
     * @param Block $block
     */
    public function remove(Block $block)
    {
        foreach ($block->getPrograms() as $program)
            $this->_em->remove($program);

        $this->_em->remove($block);
        $this->_em->flush();
    }
}