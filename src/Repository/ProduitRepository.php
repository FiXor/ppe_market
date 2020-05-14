<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Associer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }



    /**
     * @return array Produit[]
     */
    public function findShow(Produit $produits): array
    {
        // return $this->createQueryBuilder('p')
        //     ->where('p.best = :best')
        //     ->setParameter("best", "1")
        //     ->getQuery()
        //     ->execute();


        $sql = 'select produit.*, chemin From produit
Inner join presenter On produit.id=presenter.id_produit
Inner join image On presenter.id_image=image.id
WHERE produit.id = "' . $produits->getLibelle() . '" ';

        $stmt = null;
        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute([]);
        } catch (DBALException $e) {
        }

        return $stmt->fetchAll();
    }

    /**
     * @return array Produit[]
     */
    public function findBest(): array
    {
        // return $this->createQueryBuilder('p')
        //     ->where('p.best = :best')
        //     ->setParameter("best", "1")
        //     ->getQuery()
        //     ->execute();


        $sql = 'SELECT produit.*, chemin FROM produit 
INNER JOIN associer a ON a.id_produit = produit.id 
Inner join presenter On produit.id=presenter.id_produit
Inner join image On presenter.id_image=image.id
WHERE produit.best = 1';

        $stmt = null;
        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute([]);
        } catch (DBALException $e) {
        }

        return $stmt->fetchAll();
    }


    /**
     * @return array Produit[]
     */
    public function findCategorie1(Categorie $Categorie)
    {
        $sql = 'SELECT produit.*, chemin FROM produit 
INNER JOIN associer a ON a.id_produit = produit.id 
Inner join presenter On produit.id=presenter.id_produit
Inner join image On presenter.id_image=image.id
WHERE a.id_categorie = "' . $Categorie->getId() . '"';

        $stmt = null;
        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute([]);
        } catch (DBALException $e) {
        }

        return $stmt->fetchAll();
    }



    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Produit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
