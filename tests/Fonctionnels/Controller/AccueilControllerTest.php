<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of AccueilController
 *
 * @author Zawi
 */
class AccueilControllerTest extends WebTestCase
{
    public function testAccesPageAccueil() : void
    {
        $client = self::createClient();
        $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
    

}
