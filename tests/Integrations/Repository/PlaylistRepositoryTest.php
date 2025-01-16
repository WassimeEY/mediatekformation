<?php
namespace App\tests\Integrations\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Description of PlaylistRepositoryTest
 * Classe de test du repository Playlist, utilise le Kernel et le Container pour réaliser les tests.
 * @author Wassime EY
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    /**
     * Retourne le repository playlist après avoir "allumé" le Kernel et après utilisé le container pour récupérer le repository.
     * @return PlaylistRepository Le repository playlist retourné.
     */
    public function getRepository() : PlaylistRepository
    {
        self::bootKernel();
        $container = self::getContainer();
        $playlistRepository = $container->get(PlaylistRepository::class);
        return $playlistRepository;
    }
    
    /**
     * Test d'intégration permettant de vérifier que la suppression de playlist se déroule comme prévu. Se base sur l'ancien et le nouveau nombre de playlist.
     * @return void
     */
    public function testRemoveSiAucuneFormation() : void
    {
        $playlistRepository = $this->getRepository();
        $playlistSansAucuneFormation = null;
        $playlists = $playlistRepository->findAllOrderByName("ASC");
        $ancienNbPlaylists = $this->countPlaylistsArray($playlists);
        $nouveauNbPlaylists = 0;
        foreach($playlists as $playlist)
        {
            if($playlist->getFormations()->count() == 0)
            {
                $playlistSansAucuneFormation = $playlist;
            }
        }
        if(!($playlistSansAucuneFormation == null))
        {
            $playlistRepository->removeSiAucuneFormation($playlistSansAucuneFormation);
            $playlists = $playlistRepository->findAllOrderByName("ASC");
            $nouveauNbPlaylists = $this->countPlaylistsArray($playlists);
            $this->assertEquals($ancienNbPlaylists - 1, $nouveauNbPlaylists);
        }
        else
        {
            echo "Pour pouvoir réaliser le test 'testRemoveSiAucuneFormation' il faut au moins une playlist qui ne contient pas de formations dans la BDD de test 'mediatekformation_test', ce n'est pas le cas actuellement.";
        }
    }
    
    /**
     * On va vérifier que chaque playlist de l'array $result sont triées correctement.
     * @return void
     */
    public function testFindAllOrderByFormationsLen() : void
    {
        $playlistRepository = $this->getRepository();
        $resultASC = $playlistRepository->findAllOrderByFormationsLen("ASC");
        $resultDESC = $playlistRepository->findAllOrderByFormationsLen("DESC");
        $this->verifTriCorrecte($resultASC, "ASC");
        $this->verifTriCorrecte($resultDESC, "DESC");
    }
    
    /**
     * Permet de vérifier le tri de playlist que ça soit de manière croissant ou décroissant, le tri se fait sur le nombre de formations dans la playlist.
     * @param type $result La collection de playlist.
     * @param type $ordre L'ordre de tri en str, "ASC" ou "DESC".
     */
    public function verifTriCorrecte($result, $ordre)
    {
        if($ordre == "DESC")
        {
            $result = array_reverse($result);
        }
        $dernierCount = -1;
        $countActuelle = -1;
        $problemeTrie = false;
        foreach ($result as $playlist)
        {
            $countActuelle = $playlist->getFormations()->count();
            if($countActuelle >= $dernierCount)
            {
                $dernierCount = $countActuelle;
            }
            else
            {
                $problemeTrie = true;
            }
        }
        $this->assertEquals($problemeTrie, false);
    }
    
    /**
     * Permet de simplement récupérer le nombre de playlist dans la collection.
     * @param type $playlists La collection de playlist en question.
     * @return int Le compte total.
     */
    public function countPlaylistsArray($playlists) : int
    {
        $count = 0;
        foreach($playlists as $playlist)
        {
            $count++;
        }
        return $count;
    }
}
