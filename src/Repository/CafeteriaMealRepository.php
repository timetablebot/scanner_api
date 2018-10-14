<?php

namespace App\Repository;

use App\Entity\CafeteriaMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CafeteriaMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CafeteriaMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CafeteriaMeal[]    findAll()
 * @method CafeteriaMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CafeteriaMealRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CafeteriaMeal::class);
    }

//    /**
//     * @return CafeteriaMeal[] Returns an array of CafeteriaMeal objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CafeteriaMeal
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
