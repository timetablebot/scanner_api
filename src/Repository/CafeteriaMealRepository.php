<?php

namespace App\Repository;

use App\Entity\CafeteriaMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CafeteriaMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CafeteriaMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CafeteriaMeal[]    findAll()
 * @method CafeteriaMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CafeteriaMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CafeteriaMeal::class);
    }
}
