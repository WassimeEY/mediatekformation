<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des playlists
 * @author emds
 */
class PlaylistsController extends AbstractController
{
    
    /**
     * Variable du repository playlist.
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     * Variable du repository formation.
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * Variable du repository categorie.
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * Constante str pour le chemin de la page "playlists.html.twig"
     */
    private const CHEMINPLAYLISTS = "pages/playlists.html.twig";
    
    /**
     * Constructeur du controleur.
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * Gère la route d'index.
     * @Route("/playlists", name="playlists")
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Gère la route de trie des playlists.
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formations":
                $playlists = $this->playlistRepository->findAllOrderByFormationsLen($ordre);
                break;
            default:
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Gère la route de filtre des playlists.
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Gère la route de la page des détails d'une playlist.
     * @param type $id
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/playlist.html.twig", [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations
        ]);
    }
    
}
