<?php

namespace App\Repository;

use App\Entity\CategorieF;
use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fournisseur>
 *
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    public function save(Fournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fournisseur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search(string $search)
    {
        return $this->createQueryBuilder('f')
            ->select('f.id, f.nom, f.email, c.libelle, f.tel')
            ->leftJoin('f.categorie', 'c')
            ->where('f.nom LIKE :val')
            ->orWhere('c.libelle LIKE :val')
            ->orWhere('f.tel LIKE :val')
            ->orWhere('f.email LIKE :val')
            ->setParameter('val', '%'.$search.'%')
            ->getQuery()
            ->getResult();
    }


/*    public function getCatByFor()
    {
        $q = $this->createQueryBuilder('f')
            ->select('c.id, COUNT(f.categorie.id)')
            ->leftJoin('f.categorie', 'c')
            ->groupBy('c.id')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();

        $tab = [];
        foreach($q as $qq) {
            $tab[$qq['id']] = $qq[1];
        }
        return $tab;
    }*/
//    /**
//     * @return Fournisseur[] Returns an array of Fournisseur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Fournisseur
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
