<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{

    public const ITEMS_ON_PAGE = 2;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }


    public function getCommentPaginator(Conference $conference, int $pageNumber=1) : Paginator 
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.conference = :conference')
            ->setParameter('conference', $conference)
            ->andWhere('c.state = :state')
            ->setParameter('state', 'published')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(self::ITEMS_ON_PAGE)
            ->setFirstResult(($pageNumber-1)*self::ITEMS_ON_PAGE)
            ->getQuery();

        return new Paginator($query);

    }
}
