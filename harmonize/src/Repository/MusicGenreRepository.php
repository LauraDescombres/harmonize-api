<?php

namespace App\Repository;

use App\Entity\MusicGenre;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MusicGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicGenre[]    findAll()
 * @method MusicGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicGenre::class);
    }

    // /**
    //  * @return MusicGenre[] Returns an array of MusicGenre objects
    //  */
    
    public function findProjectsByMusicGenre(int $id)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin(Project::class, 'p', 'WITH', 'p.music_genre = m.id')
            ->where('p.music_genre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
   

    /*
    public function findOneBySomeField($value): ?MusicGenre
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
