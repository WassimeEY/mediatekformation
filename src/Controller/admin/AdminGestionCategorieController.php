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
 * Description of AdminGestionCategorieController
 *
 * @author Wassime EY
 */
class AdminGestionCategorieController extends AbstractController
{
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

   
    
    private const CHEMINGESTIONCATEGORIES = "/admin/pages/admin.gestioncategories.html.twig";
    
    public function __construct(CategorieRepository $categorieRepository) 
    {
        $this->categorieRepository = $categorieRepository;
    }

    
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
    
    
    #[Route('/admin/categories/suppr/{id}', name: 'gestionCategories.suppr')]
    public function supprimerCategorie($id): Response
    {
        $categorieAsuppr = $this->categorieRepository->find($id);
        $this->categorieRepository->removeSiAucuneFormation($categorieAsuppr);
        return $this->redirectToRoute('gestionCategories');
    }
    
}
