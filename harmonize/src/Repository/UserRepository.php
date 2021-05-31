<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }


    public function search($keywords)
    {
          $query = $this->createQueryBuilder('u');
          if ($keywords != null) {
              $query->where('MATCH_AGAINST(u.username) AGAINST (:keywords boolean)>0')
                  ->setParameter('keywords', $keywords);
          }

          return $query->getQuery()->getResult();
      }

    public function browseUserByRoles()
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles = :roles')
            ->andWhere('u.status = 1')
            ->setParameter('roles', '[]')
            ->getQuery()
            ->getResult()
        ;
    }

    public function readUserByRoles(int $id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles = :roles')
            ->andwhere('u.id = :id')
            ->andWhere('u.status = 1')
            ->setParameter('roles', '[]')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
