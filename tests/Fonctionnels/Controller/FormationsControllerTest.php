<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest
 *
 * @author Zawi
 */
class FormationsControllerTest extends WebTestCase
{
    
    public function testFormationsTriTitle() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/title/ASC");
        $this->assertSelectorTextContains("h5", "Android Studio (complément n°1)");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/title/DESC");
        $this->assertSelectorTextContains("h5", "UML : Diagramme de paquetages");
    }
    
    public function testFormationsTriPlaylist() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/name/ASC/playlist");
        $this->assertSelectorTextContains("h5", "Bases de la programmation n°74");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/name/DESC/playlist");
        $this->assertSelectorTextContains("h5", "C# : ListBox en couleur");
    }
    
    public function testFormationsTriDate() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/publishedAt/ASC");
        $this->assertSelectorTextContains("h5", "Cours UML (1 à 7 / 33)");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/publishedAt/DESC");
        $this->assertSelectorTextContains("h5", "Eclipse n°1");
    }
    
    public function testFormationsFiltreCategorie() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/id/categories', [
        'recherche' => 1
    ]);
        $this->assertSelectorTextContains("h5", "Eclipse n°1 : installation de l'IDE");
        $this->assertCount(9, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 8 résultats.
    }
    
    public function testFormationsFiltreTitle() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/title', [
        'recherche' => "couleur"
    ]);
        $this->assertSelectorTextContains("h5", "C# : ListBox en couleur");
        $this->assertCount(2, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 1 résultat.
    }
    
    public function testFormationsFiltrePlaylist() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/name/playlist', [
        'recherche' => "cours UML"
    ]);
        $this->assertSelectorTextContains("h5", "UML : Diagramme de paquetages");
        $this->assertCount(11, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 10 résultats.
    }
    
    public function testFormationDetail() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/formation/8");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains("h4", "Eclipse n°1 : installation de l'IDE");
    }
    
    public function getResponse($client ,$url) : Response
    {
        $client->request("GET", $url);
        return $client->getResponse();
    }
}
