<?php
namespace App\Controller\admin;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Description of AdminAccueilController
 *
 * @author Wassime EY
 */
class AdminAccueilController extends AbstractController
{
/**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository)
    {
        $this->repository = $repository;
    }
    
    #[Route('/admin', name: 'adminAccueil')]
    public function index(): Response
    {
        $formations = $this->repository->findAllLasted(2);
        return $this->render("admin/pages/admin.accueil.html.twig", [
            'formations' => $formations
        ]);
    }
    

}
