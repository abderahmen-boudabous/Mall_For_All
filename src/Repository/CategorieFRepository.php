<?php

namespace App\Repository;

use App\Entity\CategorieF;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieF>
 *
 * @method CategorieF|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieF|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieF[]    findAll()
 * @method CategorieF[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieFRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieF::class);
    }

    public function save(CategorieF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieF $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCat()
    {
        $q = $this->createQueryBuilder('f')
            ->select('f.id, f.libelle')
            ->orderBy('f.id', 'ASC')
            //->leftJoin('f.categorie', 'c')
            //->groupBy('c.id')
            ->getQuery()
            ->getResult();
        $tab = [];
        foreach($q as $qq) {
            $tab[$qq['id']] = $qq['libelle'];
        }
        return $tab;
    }

    public function getIds()
    {
        $q = $this->createQueryBuilder('c')
            ->select('c.id')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
        foreach($q as $qq) {
            $tab[] = $qq['id'];
        }
        return $q;
    }

    public function getCatByFor()
    {
        $q = $this->createQueryBuilder('c')
            ->select('c.id, COUNT(f.id)')
            ->leftJoin('c.fournisseurs', 'f')
            ->groupBy('c.id')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();

        $tab = [];
        foreach($q as $qq) {
            $tab[$qq['id']] = $qq[1];
        }
        return $tab;
    }

//    /**
//     * @return CategorieF[] Returns an array of CategorieF objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieF
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
