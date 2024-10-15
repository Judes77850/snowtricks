<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tricks>
 *
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Tricks::class);
	}

	public function findTricksWithPagination(int $offset, int $limit): array
	{
		return $this->createQueryBuilder('t')
			->orderBy('t.createdAt', 'DESC')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->getQuery()
			->getResult();
	}

	/**
	 * Vérifie s'il reste plus de tricks après un certain offset.
	 */
	public function hasMoreTricks(int $offset, int $limit): bool
	{
		return $this->createQueryBuilder('t')
				->orderBy('t.createdAt', 'DESC')
				->setFirstResult($offset)
				->setMaxResults(1)
				->getQuery()
				->getOneOrNullResult() !== null;
	}


}