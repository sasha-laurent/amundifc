<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return Game 
     */
    public function findNextGame()
    {
        $now = new \DateTime('-1 day');
        
        return $this->createQueryBuilder('m')
            ->andWhere('m.date > :date')
            ->setParameter('date', $now)
            ->orderBy('m.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Game[]
     */
    public function findNextGames()
    {
        $now = new \DateTime('-1 day');
        
        return $this->createQueryBuilder('m')
            ->andWhere('m.date > :date')
            ->setParameter('date', $now)
            ->orderBy('m.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
