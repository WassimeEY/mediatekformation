<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controlleur de l'acceuil front.
 * @author emds
 */
class AccueilController extends AbstractController
{
    
    /**
     *  Variable du repository
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur du controleur.
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
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * Gère la route des condtions générales d'utilisation.
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render("pages/cgu.html.twig");
    }
}
