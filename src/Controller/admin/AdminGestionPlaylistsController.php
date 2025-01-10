<?php
namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PlaylistFormType;
use App\Entity\Playlist;

/**
 * Description of AdminGestionPlaylistsController
 *
 * @author Wassime EY
 */
class AdminGestionPlaylistsController extends AbstractController
{
    
    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    private const CHEMINGESTIONPLAYLISTS = "/admin/pages/admin.gestionplaylists.html.twig";
    
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    
    #[Route('/admin/playlists', name: 'gestionPlaylists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'gestionPlaylists.sort')]
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
        return $this->render(self::CHEMINGESTIONPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'gestionPlaylists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('admin/playlists/playlist/modification/{id}', name: 'gestionPlaylists.modifier')]
    public function modifierPlaylist($id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $form = $this->createForm(PlaylistFormType::class, $playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute("gestionPlaylists");
        }
        return $this->render("admin/pages/admin.playlist.html.twig", [
            'playlist_form' => $form->createView()
        ]);
    }
    
    #[Route('admin/playlists/playlist/ajout', name: 'gestionPlaylists.ajout')]
    public function ajouterPlaylist(Request $request): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistFormType::class, $playlist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute("gestionPlaylists");
        }
        return $this->render("admin/pages/admin.playlist.ajout.html.twig", [
            'playlist_form' => $form->createView()
        ]);
    }
    
    #[Route('/admin/playlists/suppr/{id}', name: 'gestionPlaylists.suppr')]
    public function supprimerPlaylist($id): Response
    {
        $playlistAsuppr = $this->playlistRepository->find($id);
        $this->playlistRepository->removeSiAucuneFormation($playlistAsuppr);
        return $this->redirectToRoute('gestionPlaylists');
    }
    
}
