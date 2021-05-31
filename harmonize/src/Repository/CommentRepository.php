<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

   
    public function findCommentByProjectName(string $name)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin(Project::class, 'p', 'WITH', 'p.id = c.project')
            ->where('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCommentByUsername(string $username)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin(User::class, 'u', 'WITH', 'u.id = c.user')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
    * Function qui permet de lister les 5 projets ayant le plus de commentaires.
    */
    public function orderByHottest()
    {
        $em = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT comment.project_id, COUNT(*) AS num_comment
            FROM comment
            GROUP BY comment.project_id
            ORDER BY num_comment DESC
            LIMIT 5
            ';
        $stmt = $em->prepare($sql);
        $stmt->executeStatement();

        return $stmt->fetchAll();

        
    }
}
