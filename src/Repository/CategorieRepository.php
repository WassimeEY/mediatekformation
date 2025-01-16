<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repositoy.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * Ajout d'une entité categorie en utilisant l'entity manager.
     * @param Categorie $entity Instance à ajouter.
     * @return void
     */
    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une entité categorie en utilisant l'entity manager.
     * @param Categorie $entity Instance à supprimer.
     * @return void
     */
    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
     /**
     * Supprime la catégorie seulement si elle n'a aucune formation.
     * @param Categorie $entity
     */
    public function removeSiAucuneFormation(Categorie $entity): void
    {
        if($entity->getFormations()->count() == 0){
            $this->remove($entity);
        }
    }
    
     /**
     * Retourne toutes les catégories triées sur le nom
     * @param type $ordre
     * @return Categorie[]
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('c')
                            ->orderBy('c.name' , $ordre)
                            ->getQuery()
                            ->getResult();
    }
    
     /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')   
                ->getQuery()
                ->getResult();        
    } 
    
        /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Categorie[]
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAll();
        }
        if ($table == "") {
            return $this->createQueryBuilder('c')
                            ->where('c.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->getQuery()
                            ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                            ->join('c.' . $table, 't')
                            ->where('t.' . $champ . ' LIKE :valeur')
                            ->setParameter('valeur', '%' . $valeur . '%')
                            ->getQuery()
                            ->getResult();
        }
    }
    
}
