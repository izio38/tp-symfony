<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{
    /**
     * @param BoutiqueService $boutique
     * @return Response
     */
    public function index(BoutiqueService $boutique)
    {
        $categories = $boutique->findAllCategories();
        return $this->render('default/index.html.twig', [
            'categories' => $categories
        ]);
    }

    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }

    public function clearSession(SessionInterface $session)
    {
        $session->clear();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }
}
