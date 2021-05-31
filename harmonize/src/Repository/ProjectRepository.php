<?php

namespace App\Repository;

use App\Entity\MusicGenre;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }
    
    public function orderBy($type, $sort)
    {
        //*tri par nom
        if ($type === "name") {
            
            if ($sort === 'ASC') {
                return $this->createQueryBuilder('p')
                ->orderBy('p.name')
                ->getQuery()
                ->getResult();
            } 
            if ($sort === 'DESC') {
                return $this->createQueryBuilder('p')
                ->orderBy('p.name', 'DESC')
                ->getQuery()
                ->getResult();
            }
        };
        //*tri par date
        if ($type === "date") {
            
            if ($sort === 'ASC') {
                return $this->createQueryBuilder('p')
                ->orderBy('p.created_at')
                ->getQuery()
                ->getResult();
            } 
            if ($sort === 'DESC') {
                return $this->createQueryBuilder('p')
                ->orderBy('p.created_at', 'DESC')
                ->getQuery()
                ->getResult();
            }
        };
    }

    public function orderByGenre(int $id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.music_genre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }


    public function search($keywords)
    {
        // dd($keywords);
        $query = $this->createQueryBuilder('p');
        if ($keywords != null) {
            $query->where('MATCH_AGAINST(p.name, p.description) AGAINST (:keywords boolean)>0')
                ->setParameter('keywords', $keywords);
        }

        return $query->getQuery()->getResult();
    }


    public function findProjectsByMusicGenre(int $id)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin(MusicGenre::class, 'm', 'WITH', 'm.id = p.music_genre')
            ->where('p.music_genre = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
}
