<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use App\Service\ShopService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends AbstractController
{
    public function index($categoryId, ShopService $shop)
    {
        $productsFromCategorie = $shop->findProductsByCategoryId(
            intval($categoryId)
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
