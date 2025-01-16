<?php
namespace App\tests\Integrations\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 * Classe de test du repository Categorie, utilise le Kernel et le Container pour réaliser les tests.
 * @author Wassime EY
 */
class CategorieRepositoryTest extends KernelTestCase
{
    /**
     * Permet de rapidement récupérer le repository, en "allumant" le kernel et en utilisant le container pour récupérer le repository.
     * @return CategorieRepository Le repository en question.
     */
    public function getRepository() : CategorieRepository
    {
        self::bootKernel();
        $container = self::getContainer();
        $categorieRepository = $container->get(CategorieRepository::class);
        return $categorieRepository;
    }
    
    /**
     * Test d'intégration sur le repository Categorie, plus précisément sur la méthode findAllOrderByName(). On va vérifier que le tri se déroule comme prévu.
     * @return void
     */
    public function testFindAllOrderByName() : void
    {
        $categorieRepository = $this->getRepository();
        $resultASC = $categorieRepository->findAllOrderByName("ASC");
        $resultDESC = $categorieRepository->findAllOrderByName("DESC");
        $this->verifTriCorrecte($resultASC, "ASC");
        $this->verifTriCorrecte($resultDESC, "DESC");        
    }

    /**
     * Vérifie le tri de la collection $result basé sur l'$ordre donné.
     * @param type $result La collection.
     * @param type $ordre L'ordre du tri prévu, "ASC" ou "DESC".
     */
    public function verifTriCorrecte($result, $ordre)
    {
        if($ordre == "DESC")
        {
            $result = array_reverse($result);
        }
        $derniereLettre = 'a';
        $lettreActuelle = '';
        $problemeTrie = false;
        foreach ($result as $categorie)
        {
            $lettreActuelle = substr($categorie->getName(), 0);
            if(strcmp(strtolower($derniereLettre), strtolower($lettreActuelle)) < 0)
            {
                $derniereLettre = $lettreActuelle;
            }
            else
            {
                $problemeTrie = true;
            }
        }
        $this->assertEquals($problemeTrie, false);
    }
}
