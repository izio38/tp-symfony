<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use App\Service\ShoppingCartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShoppingCartController extends AbstractController
{
    public function index(ShoppingCartService $shoppingCartService)
    {
        $cartContent = $shoppingCartService->getExtendedContent();


        return $this->render('shopping_card/index.html.twig', [
            "cart" => $cartContent
        ]);
    }

    public function addToCart(
        ShoppingCartService $shoppingCartService,
        BoutiqueService $boutiqueService,
        Request $request
    ) {
        $productId = intval($request->request->get("productId"));
        $quantity = $request->request->get("quantity");
        $shoppingCartService->addProduct($productId, $quantity);

        $response = new Response();
        $response->setStatusCode(201);
        return $response;
    }

    public function resetCart(ShoppingCartService $shoppingCartService)
    {
        $shoppingCartService->reset();
        return $this->redirectToRoute("shopping-cart");
    }

    public function shoppingWidgetAction(ShoppingCartService $shoppingCartService) {
        $cartItemNumber = $shoppingCartService->getProductNumber();

        return $this->render("_partials/_cart_item_notification.html.twig", [ 'cartItemNumber' => $cartItemNumber ]);
    }
}
