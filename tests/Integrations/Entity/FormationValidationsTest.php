<?php
namespace App\Tests\Integrations\Entity;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationValidationsTest
 * Classe de test d'intégration sur les contraites de validation de l'entité Formation.
 * @author Wassime EY
 */
class FormationValidationsTest extends KernelTestCase
{
    /**
     * Test d'intégration sur la contrainte de validation de la variable $publishedAt de l'entité Formation, on vérifie qu'il y'a bel et bien une erreur si on définit une date dépassant celle d'ajoud'hui.
     * @return void
     */
    public function testContrainteValidationPublishedAt() : void
    {
        self::bootKernel();
        $formationTest = new Formation();
        $formationTest->setPublishedAt(new \DateTime('2028-12-28')); //On utilise le format ISO 8601 (YYYY-MM-DD HH:MM:SS)
        $container = self::getContainer();
        $validator = $container->get("validator");
        $errors = $validator->validate($formationTest);
        $this->assertCount(1, $errors);
    }
}
