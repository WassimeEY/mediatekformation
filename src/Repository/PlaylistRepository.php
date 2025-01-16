<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
/**
 * Description of CategorieRepositoryTest
 *
 * @author Wassime EY
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Supprime la playlist si et seulement si elle n'a aucune formation.
     * @param Playlist $entity
     */
    public function removeSiAucuneFormation(Playlist $entity): void
    {
        if($entity->getFormations()->count() == 0){
            $this->remove($entity);
        }
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();
    }

   
    /**
     * Retourne toutes les playlists triées sur le nombre de formation qu'elle contient
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByFormationsLen($ordre): array
    {
        $playlistsAvecAucuneFormations = $this->createQueryBuilder('p')
                ->where("SIZE(p.formations) = 0")
                ->getQuery()
                ->getResult();
        $result = $this->createQueryBuilder('p')
                ->leftJoin('p.formations', 'f')
                ->groupBy('f.playlist')
                ->orderBy('SIZE(p.formations)', $ordre)
                ->having("SIZE(p.formations) <> 0")
                ->getQuery()
                ->getResult();
        if($ordre == "ASC")
        {
           return array_merge($playlistsAvecAucuneFormations, $result); 
        }
        else
        {
           return array_merge($result, $playlistsAvecAucuneFormations);  
        }
    }
    

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        if ($table == "") {
            return $this->createQueryBuilder('p')
                            ->leftjoin('p.formations', 'f')
                            ->where('p.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy('p.id')
                            ->orderBy('p.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                            ->leftjoin('p.formations', 'f')
                            ->leftjoin('f.categories', 'c')
                            ->where('c.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->groupBy('p.id')
                            ->orderBy('p.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        }
    }
        
}
