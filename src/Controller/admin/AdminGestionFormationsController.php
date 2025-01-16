<?php
namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormationFormType;
use App\Entity\Formation;

/**
 * Controleur coté admin de la gestion des formations.
 * @author Wassime EY
 */
class AdminGestionFormationsController extends AbstractController
{

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
     * Constante str pour le chemin vers la page de gestion des formations.
     */
    private const CHEMINGESTIONFORMATION = "admin/pages/admin.gestionformations.html.twig";
    
    /**
     * Constructeur du controleur.
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * Gère la route d'index
     * @return Response
     */
    #[Route('/admin/formations', name: 'gestionFormations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONFORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Gère la route de trie des formations sur la page de gestion.
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'gestionFormations.sort')]
    public function sort($champ, $ordre, $table=""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONFORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Gère la route de recherche, et donc de filtre, ici pour les formations.
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'gestionFormations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONFORMATION, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Gère la route de modification d'une formation, on réutilise la méthode 'add' du repository qui va automatiquement modifier au lieu d'ajouter, on gère également le formulaire et on se prépare à réagir avec un if au cas où le formulaire est validé.
     * @param type $id Id de la formation à modifier.
     * @param Request $request
     * @return Response
     */
    #[Route('admin/formations/formation/modification/{id}', name: 'gestionFormations.modifier')]
    public function modifierFormation($id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute("gestionFormations");
        }
        return $this->render("admin/pages/admin.formation.html.twig", [
            'formation_form' => $form->createView()
        ]);
    }
    
    /**
     * Gère la route d'ajout d'une formation, on utilise la méthode 'add' du repository, on gère également le formulaire et on se prépare à réagir avec un if au cas où le formulaire est validé.
     * @param Request $request
     * @return Response
     */
    #[Route('admin/formations/formation/ajout', name: 'gestionFormations.ajout')]
    public function ajouterFormation(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->formationRepository->add($formation);
            return $this->redirectToRoute("gestionFormations");
        }
        return $this->render("admin/pages/admin.formation.ajout.html.twig", [
            'formation_form' => $form->createView()
        ]); 
    }
    
    /**
     * Gère la route de suppression d'une formation, on utilise la méthode 'remove' du repository.
     * @param type $id Id de la formation à supprimer.
     * @return Response
     */
    #[Route('/admin/formations/suppr/{id}', name: 'gestionFormations.suppr')]
    public function supprimerFormation($id): Response
    {
        $formationAsuppr = $this->formationRepository->find($id);
        $this->formationRepository->remove($formationAsuppr);
        return $this->redirectToRoute('gestionFormations');
    }

}

