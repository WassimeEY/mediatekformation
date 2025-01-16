<?php
namespace App\Controller\admin;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controleur coté admin de l'acceuil.
 * @author Wassime EY
 */
class AdminAccueilController extends AbstractController
{
    /**
     * 
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Le constructeur du controleur.
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Gère la route d'index.
     * @return Response
     */
    #[Route('/admin', name: 'adminAccueil')]
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("admin/pages/admin.accueil.html.twig", [
            'formations' => $formations
        ]);
    }
    

}
