<?php
namespace App\tests\Fonctionnels\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of FormationsControllerTest
 * Classe de test fonctionnel du contrôlleur Formations, on crée un client et on l'utilise pour avoir des réponses HTTP après l'envoie de requête.
 * @author Wassime EY
 */
class FormationsControllerTest extends WebTestCase
{
    
    /**
     * Test fonctionnel permettant de vérifier que le tri des formations sur leurs titres fonctionne comme prévu. Se base sur le premier élément <h5> de la page.
     * @return void
     */
    public function testFormationsTriTitle() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/title/ASC");
        $this->assertSelectorTextContains("h5", "Android Studio (complément n°1)");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/title/DESC");
        $this->assertSelectorTextContains("h5", "UML : Diagramme de paquetages");
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le tri des formations sur leurs playlists, ou plutôt le nom de la playlist, fonctionne comme prévu. Se base sur le premier élément <h5> de la page.
     * @return void
     */
    public function testFormationsTriPlaylist() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/name/ASC/playlist");
        $this->assertSelectorTextContains("h5", "Bases de la programmation n°74");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/name/DESC/playlist");
        $this->assertSelectorTextContains("h5", "C# : ListBox en couleur");
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le tri des formations sur leurs dates fonctionne comme prévu. Se base sur le premier élément <h5> de la page.
     * @return void
     */
    public function testFormationsTriDate() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/tri/publishedAt/ASC");
        $this->assertSelectorTextContains("h5", "Cours UML (1 à 7 / 33)");
        //----------------------------------
        $this->getResponse($client,"/formations/tri/publishedAt/DESC");
        $this->assertSelectorTextContains("h5", "Eclipse n°1");
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le filtre des formations sur leurs catégories fonctionne comme prévu. Se base sur le premier élément <h5> de la page et sur le nombre d'élément <tr>, en prenant en compte que l'en tête de la liste est aussi un <tr>.
     * @return void
     */
    public function testFormationsFiltreCategorie() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/id/categories', [
        'recherche' => 1
    ]);
        $this->assertSelectorTextContains("h5", "Eclipse n°1 : installation de l'IDE");
        $this->assertCount(9, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 8 résultats.
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le filtre des formations sur leurs titres fonctionne comme prévu. Se base sur le premier élément <h5> de la page et sur le nombre d'élément <tr>, en prenant en compte que l'en tête de la liste est aussi un <tr>.
     * @return void
     */
    public function testFormationsFiltreTitle() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/title', [
        'recherche' => "couleur"
    ]);
        $this->assertSelectorTextContains("h5", "C# : ListBox en couleur");
        $this->assertCount(2, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 1 résultat.
    }
    
    /**
     * Test fonctionnel permettant de vérifier que le filtre des formations sur leurs noms de playlists fonctionne comme prévu. Se base sur le premier élément <h5> de la page et sur le nombre d'élément <tr>, en prenant en compte que l'en tête de la liste est aussi un <tr>.
     * @return void
     */
    public function testFormationsFiltrePlaylist() : void
    {
        $client = self::createClient();
        $crawler = $client->request('POST', '/formations/recherche/name/playlist', [
        'recherche' => "cours UML"
    ]);
        $this->assertSelectorTextContains("h5", "UML : Diagramme de paquetages");
        $this->assertCount(11, $crawler->filter('tr')); //On doit ajouter 1 dans le nb attendu car la balise tr est utilisé pour l'en tête de la liste de formation, donc on attend ici 10 résultats.
    }
    
    /**
     * Test fonctionnel qui va tenter de se connecter à la page de détail d’une formation, ici la formation d'id 8, et vérifiera que le code retourné par la réponse est bien 200, ensuite la méthode va vérifier que le premier élément <h4> est celui attendu.
     * @return void
     */
    public function testFormationDetail() : void
    {
        $client = self::createClient();
        $this->getResponse($client,"/formations/formation/8");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains("h4", "Eclipse n°1 : installation de l'IDE");
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
