<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByPriceRange($minValue, $maxValue)
{
    return $this->createQueryBuilder('a')
        ->andWhere('a.price >= :minVal')
        ->setParameter('minVal', $minValue)
        ->andWhere('a.price <= :maxVal')
        ->setParameter('maxVal', $maxValue)
        ->orderBy('a.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
}

public function findSearch(SearchData $search): array
{
    $query= $this->createQueryBuilder('p')
        ->select('c', 'p')
        ->join('p.shop', 'c');

    if(!empty($search->q)){
        $query = $query
            ->andWhere('p.name LIKE :q')
            ->setParameter('q', "%{$search->q}%");
    }
    if(!empty($search->min)){
        $query = $query
            ->andWhere('p.price >= :min')
            ->setParameter('min', $search->min);
    }
    if(!empty($search->max)){
        $query = $query
            ->andWhere('p.price <= :max')
            ->setParameter('max', $search->max);
    }
    if(!empty($search->stock)){
        $query = $query
            ->andWhere('p.stock != 0');          
    }
    if(!empty($search->shop)){
        $query = $query
            ->andWhere('c.id IN (:shop)')
            ->setParameter('shop', $search->shop);
    }
    

    return $query->getQuery()->getResult();
}



    


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    


}
