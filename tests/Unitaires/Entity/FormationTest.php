<?php

namespace App\Tests\Unitaires\Entity;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\TestCase;
/**
 * Description of FormationTest
 * Classe de test unitaire sur l'entité Formation.
 * @author Wassime EY
 */
class FormationTest extends TestCase
{
    /**
     * Test unitaire qui permet de vérifier que la méthode getPublishedAtString() de l'entité Formation fonctionne comme prévu.
     * @return void
     */
    public function testGetPublishedAtString() : void
    {
        $formationTest = new Formation();
        $dateTest = new DateTime('2025-01-04 17:00:12'); //On utilise le format ISO 8601 (YYYY-MM-DD HH:MM:SS)
        $formationTest->setPublishedAt($dateTest);
        $this->assertEquals('04/01/2025', $formationTest->getPublishedAtString()); 
    }
}
