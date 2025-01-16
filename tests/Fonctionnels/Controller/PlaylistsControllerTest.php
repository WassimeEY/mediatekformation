<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistsControllerTest
 * Classe de test fonctionnel du contrôlleur Playlists, on crée un client et on l'utilise pour avoir des réponses HTTP après l'envoie de requête.
 * @author Wassime EY
 */
class PlaylistsControllerTest extends WebTestCase
{
    /**
     * Test fonctionnel permettant de vérifier que le tri des playlists sur leurs noms fonctionne comme prévu. Se base sur le premier élément <h5> de la page.
     * @return void
     */
    public function testPlaylistsTriName() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/tri/name/ASC");
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#");
        //----------------------------------
        $this->getResponse($client,"/playlists/tri/name/DESC");
        $this->assertSelectorTextContains("h5", "Visual Studio 2019 et C#");
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le tri des playlists sur leurs nombres de formations fonctionne comme prévu. Se base sur le premier élément <h5> de la page.
     * @return void
     */
    public function testPlaylistsTriNbFormations() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/tri/formations/ASC");
        $this->assertSelectorTextContains("h5", "Eclipse et Java");
        //----------------------------------
        $this->getResponse($client,"/playlists/tri/formations/DESC");
        $this->assertSelectorTextContains("h5", "Bases de la programmation (C#");
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le filtre des playlists sur leurs catégories fonctionne comme prévu. Se base sur le premier élément <h5> de la page et sur le nombre d'élément <tr>, en prenant en compte que l'en tête de la liste est aussi un <tr>.
     * @return void
     */
    public function testPlaylistsFiltreCategorie() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/playlists/recherche/id/categories', [
        'recherche' => 1
    ]);
        $this->assertSelectorTextContains("h5", "Eclipse et Java");
        $this->assertCount(4, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de playlist, donc on attend ici 3 résultats.
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le filtre des playlists sur leurs noms fonctionne comme prévu. Se base sur le premier élément <h5> de la page et sur le nombre d'élément <tr>, en prenant en compte que l'en tête de la liste est aussi un <tr>.
     * @return void
     */
    public function testPlaylistsFiltreName() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/playlists/recherche/name', [
        'recherche' => "TP Android"
    ]);
        $this->assertSelectorTextContains("h5", "TP Android (programmation mobile");
        $this->assertCount(2, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de playlist, donc on attend ici 1 résultat.
    }
    
    /**
     * Test fonctionnel qui va tenter de se connecter à la page de détail d’une playlist, ici la playlist d'id 13, et vérifiera que le code retourné par la réponse est bien 200, ensuite la méthode va vérifier que le premier élément <h4> est celui attendu.
     * @return void
     */
    public function testPlaylistDetail() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/playlists/playlist/13");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains("h4", "Bases de la programmation (C#)");
    }
    
    /**
     * Permet de rapidement charger une page et de récupérer la réponse.
     * @param type $client Le client qui permettra de gérer la reqûete.
     * @param type $url L'url ou plutôt le chemin "path" d'url vers la page.
     * @return Response
     */
    public function getResponse($client ,$url) : Response
    {
        $client->request("GET", $url);
        return $client->getResponse();
    }
}
