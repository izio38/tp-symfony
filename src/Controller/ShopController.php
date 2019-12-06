<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends AbstractController
{
    public function index($categoryId, BoutiqueService $shopService)
    {
        $productsFromCategorie = $shopService->findProduitsByCategorie(
            $categoryId
        );
        return $this->render('shop/index.html.twig', [
            'products' => $productsFromCategorie
        ]);
    }

    /**
     * @param Request $request
     * @param BoutiqueService $shopService
     * @return Response
     */
    public function search(Request $request, BoutiqueService $shopService)
    {
        if ($request->getMethod() == "POST") {
            $keyword = $request->request->get('keyword', null);

            $result = $shopService->findProduitsByLibelleOrTexte($keyword);

            if (count($result) > 0) {
                return $this->render("shop/search-result.html.twig", [
                    'products' => $result
                ]);
            }

            return $this->render("shop/search-result.html.twig", [
                'products' => []
            ]);
        }
        return $this->render("shop/search-result.html.twig", [
            'products' => []
        ]);
    }
}
