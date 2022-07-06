<?php

namespace App\Repository;

use App\Entity\WaitGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WaitGame>
 *
 * @method WaitGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method WaitGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method WaitGame[]    findAll()
 * @method WaitGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WaitGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WaitGame::class);
    }

    public function add(WaitGame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WaitGame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return WaitGame[] Returns an array of WaitGame objects
    */
   public function findMatchElo($minElo, $maxElo, $userID): array
   {
       return $this->createQueryBuilder('w')
           ->andWhere('w.MinElo >= :val1')
           ->andWhere('w.MaxElo <= :val2')
           ->andWhere('w.UserID <> :val3')
           ->setParameter('val1', $minElo)
           ->setParameter('val2', $maxElo)
           ->setParameter('val3', $userID)
           ->orderBy('w.WaitAt', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneByID($userID): ?WaitGame
   {
       return $this->createQueryBuilder('w')
           ->andWhere('w.UserID = :val')
           ->setParameter('val', $userID)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
