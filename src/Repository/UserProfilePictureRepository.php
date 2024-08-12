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
}
