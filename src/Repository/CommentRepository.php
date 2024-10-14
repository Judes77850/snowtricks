<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

	public function findCommentsByTrickId($trickId, $offset = 0, $limit = 10)
	{
		return $this->createQueryBuilder('c')
			->where('c.trickId = :trickId')
			->setParameter('trickId', $trickId)
			->orderBy('c.createdAt', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	public function hasMoreComments($trickId, $offset, $limit)
	{
		return $this->createQueryBuilder('c')
				->where('c.trickId = :trickId')
				->setParameter('trickId', $trickId)
				->orderBy('c.createdAt', 'DESC')
				->setFirstResult($offset + $limit)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult() !== null;
	}

}
