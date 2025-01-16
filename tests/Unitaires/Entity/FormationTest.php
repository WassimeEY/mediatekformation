<?php

namespace App\Tests\Unitaires\Entity;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\assertEquals;
use PHPUnit\Framework\TestCase;
/**
 * Description of FormationTest
 *
 * @author Zawi
 */
class FormationTest extends TestCase
{
    public function testGetPublishedAtString() : void
    {
        $formationTest = new Formation();
        $dateTest = new DateTime('2025-01-04 17:00:12'); //On utilise le format ISO 8601 (YYYY-MM-DD HH:MM:SS)
        $formationTest->setPublishedAt($dateTest);
        $this->assertEquals('04/01/2025', $formationTest->getPublishedAtString()); 
    }
}
