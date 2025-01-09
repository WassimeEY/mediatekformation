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
 * Description of AdminGestionFormationsController
 *
 * @author Zawi
 */
class AdminGestionFormationsController extends AbstractController
{

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
    
    private const CHEMINGESTIONFORMATION = "admin/pages/admin.gestionformations.html.twig";
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
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

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'gestionFormations.sort')]
    public function sort($champ, $ordre, $table=""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMINGESTIONFORMATION, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/recherche/{champ}/{table}', name: 'gestionFormations.findallcontain')]
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
    
    #[Route('/admin/formations/suppr/{id}', name: 'gestionFormations.suppr')]
    public function supprimerFormation($id): Response
    {
        $formationAsuppr = $this->formationRepository->find($id);
        $this->formationRepository->remove($formationAsuppr);
        return $this->redirectToRoute('gestionFormations');
    }

}

