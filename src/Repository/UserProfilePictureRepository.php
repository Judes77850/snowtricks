<?php

namespace App\Repository;

use App\Entity\UserProfilePicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfilePicture>
 *
 * @method UserProfilePicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfilePicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfilePicture[]    findAll()
 * @method UserProfilePicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfilePictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfilePicture::class);
    }

//    /**
//     * @return UserProfilePicture[] Returns an array of UserProfilePicture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserProfilePicture
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
