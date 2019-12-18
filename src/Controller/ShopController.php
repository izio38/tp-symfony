<?php

namespace App\Controller;

use App\Service\ShopService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends AbstractController
{
    public function index($categoryId, ShopService $shop)
    {
        $productsFromCategory = $shop->findProductsByCategoryId(
            intval($categoryId)
        );
        return $this->render('shop/index.html.twig', [
            'products' => $productsFromCategory
        ]);
    }

    /**
     * @param Request $request
     * @param ShopService $shop
     * @return Response
     */
    public function search(Request $request, ShopService $shop)
    {
        if ($request->getMethod() == "POST") {
            $keyword = $request->request->get('keyword', null);

            // It finds result by label or description
            $result = $shop->findByLikeOrDescriptionLabel($keyword);

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
