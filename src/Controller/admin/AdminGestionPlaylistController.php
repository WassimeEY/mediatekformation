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
 * Controleur coté admin de la gestion des playlists.
 * @author Wassime EY
 */
class AdminGestionPlaylistController extends AbstractController
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
     * Constante str pour le chemin vers la page de gestion des playlists.
     */
    private const CHEMINGESTIONPLAYLISTS = "/admin/pages/admin.gestionplaylists.html.twig";
    
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
     * Gère la route d'index
     * @return Response
     */
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

    /**
     * Gère la route de trie des playlists sur la page de gestion.
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
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

    /**
     * Gère la route de recherche, et donc de filtre, ici pour les playlists.
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
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

    /**
     * Gère la route de modification d'une playlist, on réutilise la méthode 'add' du repository qui va automatiquement modifier au lieu d'ajouter, on gère également le formulaire et on se prépare à réagir avec un if au cas où le formulaire est validé.
     * @param type $id Id de la playlist à modifier.
     * @param Request $request
     * @return Response
     */
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
    
    /**
     * Gère la route d'ajout d'une playlist, on utilise la méthode 'add' du repository, on gère également le formulaire et on se prépare à réagir avec un if au cas où le formulaire est validé.
     * @param Request $request
     * @return Response
     */
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
    
    /**
     * Gère la route de suppression d'une playlist, on utilise la méthode 'remove' du repository.
     * @param type $id Id de la playlist à supprimer.
     * @return Response
     */
    #[Route('/admin/playlists/suppr/{id}', name: 'gestionPlaylists.suppr')]
    public function supprimerPlaylist($id): Response
    {
        $playlistAsuppr = $this->playlistRepository->find($id);
        $this->playlistRepository->removeSiAucuneFormation($playlistAsuppr);
        return $this->redirectToRoute('gestionPlaylists');
    }
    
}
