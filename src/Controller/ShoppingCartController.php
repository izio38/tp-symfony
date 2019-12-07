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
        $reversedCartContent = array_reverse($cartContent);

        return $this->render('shopping_card/index.html.twig', [
            "cart" => $reversedCartContent
        ]);
    }

    public function addToCart(
        $productId,
        ShoppingCartService $shoppingCartService,
        Request $request
    ) {
        if ($request->getMethod() == "POST") {
            $productId = intval($request->request->get("productId"));
            $quantity = $request->request->get("quantity");
            $shoppingCartService->addProduct($productId, $quantity);
        } else {
            $shoppingCartService->addProduct($productId, 1);
        }

        if ($request->getMethod() == "POST") {
            $response = new Response();
            $response->setStatusCode(201);
            return $response;
        }

        return $this->redirectToRoute("shopping-cart");
    }

    public function removeFromCart(
        $productId,
        ShoppingCartService $shoppingCartService
    ) {
        $parsedProductId = intval($productId);
        $shoppingCartService->decreaseProductQuantity($parsedProductId, 1);

        return $this->redirectToRoute("shopping-cart");
    }

    public function resetCart(ShoppingCartService $shoppingCartService)
    {
        $shoppingCartService->reset();
        return $this->redirectToRoute("shopping-cart");
    }

    public function resetCartItem($productId, ShoppingCartService $shoppingCartService)
    {
        $parsedProductId = intval($productId);
        $shoppingCartService->removeProduct($parsedProductId);

        return $this->redirectToRoute("shopping-cart");
    }

    public function shoppingWidgetAction(
        ShoppingCartService $shoppingCartService
    ) {
        $cartItemNumber = $shoppingCartService->getProductNumber();

        return $this->render("_partials/_cart_item_notification.html.twig", [
            'cartItemNumber' => $cartItemNumber
        ]);
    }
}
