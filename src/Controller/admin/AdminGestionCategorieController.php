<?php
namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Form\CategorieFormType;

/**
 * Controleur coté admin de la gestion des catégories.
 * @author Wassime EY
 */
class AdminGestionCategorieController extends AbstractController
{
    
    /**
     * Variable du repository categorie.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constante str pour le chemin vers la page de gestion des catégories.
     */
    private const CHEMINGESTIONCATEGORIES = "/admin/pages/admin.gestioncategories.html.twig";
    
    /**
     * Constructeur du controleur.
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository) 
    {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Gère la route d'index
     * @return Response
     */
    #[Route('/admin/categories', name: 'gestionCategories')]
    public function index(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();
        $categorie = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute("gestionCategories");
        }
        return $this->render(self::CHEMINGESTIONCATEGORIES, [
            'categories' => $categories,
            'categorie_form' => $form->createView()
        ]);
    }

    /**
     * Gère la route de trie des catégorie sur la page de gestion.
     * @param type $ordre
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories/tri/name/{ordre}', name: 'gestionCategories.sort')]
    public function sort($ordre, Request $request): Response
    {
        $categories = $this->categorieRepository->findAllOrderByName($ordre);
        $categorie = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute("gestionCategories");
        }
        return $this->render(self::CHEMINGESTIONCATEGORIES, [
            'categories' => $categories,
            'categorie_form' => $form->createView()
        ]);
    }

    /**
     * Gère la route de recherche, et donc de filtre, ici pour les categories.
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/categories/recherche/{champ}', name: 'gestionCategories.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $categories = $this->categorieRepository->findByContainValue($champ, $valeur, $table);
        $categorie = new Categorie();
        $form = $this->createForm(CategorieFormType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute("gestionCategories");
        }
        return $this->render(self::CHEMINGESTIONCATEGORIES, [
            'categories' => $categories,
            'categorie_form' => $form->createView(),
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /**
     * Gère la route de suppression de catégorie, il n'est pas possible de supprimer une catégorie si elle a des formations.
     * @param type $id Id de la catégorie à supprimer.
     * @return Response
     */
    #[Route('/admin/categories/suppr/{id}', name: 'gestionCategories.suppr')]
    public function supprimerCategorie($id): Response
    {
        $categorieAsuppr = $this->categorieRepository->find($id);
        $this->categorieRepository->removeSiAucuneFormation($categorieAsuppr);
        return $this->redirectToRoute('gestionCategories');
    }
    
}
