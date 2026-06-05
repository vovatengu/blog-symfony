<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\SonataUserUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
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

    public function findaAllActivePosts(): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('p.createdAt', 'DESC');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function deactivateOlderPosts(\DateTimeInterface $date): int
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->update()
            ->set('p.isActive', ':inactive')
            ->andWhere('p.createdAt < :date')
            ->andWhere('p.isActive = :active')
            ->setParameter('inactive', false)
            ->setParameter('date', $date)
            ->setParameter('active', true);

        return $queryBuilder->getQuery()->execute();
    }

    public function findByAuthor(SonataUserUser $author): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.author = :author')
            ->setParameter('author', $author)
            ->orderBy('p.id', 'DESC');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function resolveUniqueSlug(string $slug): string
    {
        if (!$this->slugExists($slug)) {
            return $slug;
        }

        $suffix = 2;
        do {
            $candidate = $slug.'-'.$suffix;
            ++$suffix;
        } while ($this->slugExists($candidate));

        return $candidate;
    }

    private function slugExists(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}
