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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

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
     * Retourne toutes les catégories triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @param type $table si $champ dans une autre table
     * @return Categorie[]
     */
    public function findAllOrderBy($champ, $ordre, $table = ""): array
    {
        if ($table == "") {
            return $this->createQueryBuilder('c')
                            ->orderBy('c.' . $champ, $ordre)
                            ->getQuery()
                            ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                            ->join('c.' . $table, 't')
                            ->orderBy('t.' . $champ, $ordre)
                            ->getQuery()
                            ->getResult();
        }
    }
    
        /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
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
