<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 *
 * @author Zawi
 */
class PlaylistsControllerTest extends WebTestCase
{
    public function testPlaylistsTriName() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/tri/name/ASC");
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#");
        //----------------------------------
        $this->getResponse($client,"/playlists/tri/name/DESC");
        $this->assertSelectorTextContains("h5", "Visual Studio 2019 et C#");
    }
    
    public function testPlaylistsTriNbFormations() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/tri/formations/ASC");
        $this->assertSelectorTextContains("h5", "Eclipse et Java");
        //----------------------------------
        $this->getResponse($client,"/playlists/tri/formations/DESC");
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#");
    }
    
    public function testPlaylistsFiltreCategorie() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/playlists/recherche/id/categories', [
        'recherche' => 1
    ]);
        $this->assertSelectorTextContains("h5", "Eclipse et Java");
        $this->assertCount(4, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de playlist, donc on attend ici 3 résultats.
    }
    
    public function testPlaylistsFiltreName() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/playlists/recherche/name', [
        'recherche' => "TP Android"
    ]);
        $this->assertSelectorTextContains("h5", "TP Android (programmation mobile");
        $this->assertCount(2, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de playlist, donc on attend ici 1 résultat.
    }
    
    public function testPlaylistDetail() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/playlist/13");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains("h4", "Bases de la programmation (C#)");
    }
    
    public function getResponse($client ,$url) : Response
    {
        $client->request("GET", $url);
        return $client->getResponse();
    }
}
