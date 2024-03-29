<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
    public function findPostsByCategory(int $category_id) : array
    {
        return $this->createQueryBuilder('p')
             ->innerJoin('p.category', 'c')
             ->andWhere('p.isPublished = :val')
             ->andWhere('p.category = :category_id')
             ->setParameter('val', true)
             ->setParameter('category_id', $category_id)
             ->orderBy('p.id', "ASC")
             ->getQuery()
             ->getResult()
        ;
    }


    public function findPostsByTag(int $tag_id) : array
    {
        return $this->createQueryBuilder('p')
                    ->join('p.tags', 't')
                    ->select('p')
                    ->where('t.id = :id')
                    ->andWhere('p.isPublished = :val')
                    ->setParameter('id',  $tag_id)
                    ->setParameter('val', true)
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
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

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
