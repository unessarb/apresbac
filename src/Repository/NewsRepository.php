<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function add(News $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(News $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function createAllActiveNewsQuery(): Query
    {
        $currentDate = new \DateTime();
        $queryBuilder = $this->createQueryBuilder('n')
            ->andWhere('n.publishedAt <= :date')
            ->andWhere('n.isActive = :isActive')
            ->andWhere('n.isPublic = :isPublic')
            ->setParameters([
                'date' => $currentDate->format('Y-m-d H:i:s'),
                'isActive' => true,
                'isPublic' => true,
            ])
            ->orderBy('n.publishedAt', 'DESC');


        return $queryBuilder->getQuery();
    }

    public function getActiveNews($limit): array
    {
        $currentDate = new \DateTime();
        return $this->createQueryBuilder('n')
            ->andWhere('n.publishedAt <= :date')
            ->andWhere('n.isActive = :isActive')
            ->andWhere('n.isPublic = :isPublic')
            ->setParameters([
                'date' => $currentDate->format('Y-m-d H:i:s'),
                'isActive' => true,
                'isPublic' => true,
            ])
            ->orderBy('n.publishedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getOtherNews(News $news): array
    {
        $currentDate = new \DateTime();
        return $this->createQueryBuilder('n')
            ->andWhere('n.publishedAt <= :date')
            ->andWhere('n.isActive = :isActive')
            ->andWhere('n != :news')
            ->andWhere('n.isPublic = :isPublic')
            ->setParameters([
                'date' => $currentDate->format('Y-m-d H:i:s'),
                'news' => $news,
                'isActive' => true,
                'isPublic' => true,
            ])
            ->orderBy('n.publishedAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return News[] Returns an array of News objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('n.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?News
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
