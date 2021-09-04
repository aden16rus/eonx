<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }
    
    /**
     * @param int $page
     * @param int $quantity
     *
     * @return array
     */
    public function paginate(int $page, int $quantity): array
    {
        $offset = ($page - 1) * $quantity;
        return $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults($quantity)
            ->getQuery()
            ->getArrayResult();
    }
    
    /**
     * @param int $id
     *
     * @return array|null
     */
    public function getArray(int $id): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }
}
