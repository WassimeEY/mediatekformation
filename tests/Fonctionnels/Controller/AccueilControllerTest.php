<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of AccueilController
 * Classe de test fonctionnel du contrôlleur Acceuil, on crée un client et on l'utilise pour avoir des réponses HTTP après l'envoie de requête.
 * @author Wassime EY
 */
class AccueilControllerTest extends WebTestCase
{
    /**
     * Test fonctionnel qui va tenter de se connecter à la page d'acceuil, et vérifiera que le code retourné par la réponse est bien 200 pour OK.
     * @return void
     */
    public function testAccesPageAccueil() : void
    {
        $client = self::createClient();
        $client->request('GET', '/');
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
    

}
