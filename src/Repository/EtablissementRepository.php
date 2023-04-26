<?php

namespace App\Repository;

use App\Entity\Etablissement;
use App\Entity\Secteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Etablissement>
 *
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }

    public function add(Etablissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etablissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Etablissement[] Returns an array of Etablissement objects
     */
    public function createAllEtablissementBySecteurQuery(Secteur $secteur): Query
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->andWhere(':secteur MEMBER OF e.secteurs')
            ->andWhere('e.isPublic = :isPublic')
            ->andWhere('e.isActive = :isActive')
            ->setParameter('secteur', $secteur)
            ->setParameter('isPublic', true)
            ->setParameter('isActive', true)
            ->orderBy('e.name', 'ASC');


        return $queryBuilder->getQuery();
    }

    /**
     * @return Etablissement[] Returns an array of Etablissement objects
     */
    public function createAllEtablissementQuery($isEtranger = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->andWhere('e.isPublic = :isPublic')
            ->andWhere('e.isActive = :isActive')
            ->andWhere('e.isEtranger = :isEtranger')
            ->setParameter('isPublic', true)
            ->setParameter('isActive', true)
            ->setParameter('isEtranger', $isEtranger)
            ->orderBy('e.name', 'ASC');


        return $queryBuilder->getQuery();
    }

    /**
     * @return Etablissement[] Returns an array of Etablissement objects
     */
    public function createSearchEtablissementQuery($mots, $isEtranger = false): Query
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->andWhere('MATCH_AGAINST(e.name, e.sigle, e.tagsText, e.secteursText, e.villesText) AGAINST (:mots boolean)>0')
            ->andWhere('e.isPublic = :isPublic')
            ->andWhere('e.isActive = :isActive')
            ->andWhere('e.isEtranger = :isEtranger')
            ->setParameter('mots', $mots)
            ->setParameter('isPublic', true)
            ->setParameter('isActive', true)
            ->setParameter('isEtranger', $isEtranger)
            ->orderBy('e.name', 'ASC');


        return $queryBuilder->getQuery();
    }

    //    public function findOneBySomeField($value): ?Etablissement
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
