<?php
namespace App\tests\Integrations\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



/**
 * Description of PlaylistRepositoryTest
 *
 * @author Zawi
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    public function getRepository() : PlaylistRepository
    {
        self::bootKernel();
        $container = self::getContainer();
        $playlistRepository = $container->get(PlaylistRepository::class);
        return $playlistRepository;
    }
    
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
