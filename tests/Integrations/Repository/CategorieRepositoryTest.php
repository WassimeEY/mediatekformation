<?php
namespace App\tests\Integrations\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 *
 * @author Wassime EY
 */
class CategorieRepositoryTest extends KernelTestCase
{
    public function getRepository() : CategorieRepository
    {
        self::bootKernel();
        $container = self::getContainer();
        $categorieRepository = $container->get(CategorieRepository::class);
        return $categorieRepository;
    }
    
    public function testFindAllOrderByName() : void
    {
        $categorieRepository = $this->getRepository();
        $resultASC = $categorieRepository->findAllOrderByName("ASC");
        $resultDESC = $categorieRepository->findAllOrderByName("DESC");
        $this->verifTriCorrecte($resultASC, "ASC");
        $this->verifTriCorrecte($resultDESC, "DESC");        
    }

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
