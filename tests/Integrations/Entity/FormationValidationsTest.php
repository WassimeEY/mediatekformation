<?php
namespace App\Tests\Integrations\Entity;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of FormationValidationsTest
 *
 * @author Zawi
 */
class FormationValidationsTest extends KernelTestCase
{
    public function testTruc() : void
    {
        self::bootKernel();
        $formationTest = new Formation();
        $formationTest->setPublishedAt(new \DateTime('2028-12-28')); //On utilise le format ISO 8601 (YYYY-MM-DD HH:MM:SS)
        $container = self::getContainer();
        $validator = $container->get("validator");
        $errors = $validator->validate($formationTest);
        $this->assertCount(1, $errors);
        foreach ($errors as $err) {
            echo $err->getMessage();  
        }
        }
}
