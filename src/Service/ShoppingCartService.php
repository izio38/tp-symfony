<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\BoutiqueService;

class ShoppingCartService
{
    const CART_SESSION = 'cart';
    private $session;
    private $shop;
    private $cart;

    public function __construct(
        SessionInterface $session,
        BoutiqueService $shopService
    ) {
        $this->shop = $shopService;
        $this->session = $session;

        $this->cart = $session->get(self::CART_SESSION, array());
    }

    public function getContent()
    {
        return $this->cart;
    }

    public function getExtendedContent()
    {
        return array_map(array($this, 'addExtendedInformation'), $this->cart);
    }

    public function addExtendedInformation($cartItem)
    {
        $product = $this->shop->findProduitById($cartItem["id"]);
        $product["quantity"] = $cartItem["quantity"];
        return $product;
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;
        foreach ($this->cart as $cartProduct) {
            $product = $this->shop->findProduitById($cartProduct["id"]);
            $totalPrice += $product["prix"];
        }
        return $totalPrice;
    }

    public function getProductNumber()
    {
        return count($this->cart);
    }

    public function addProduct(int $productId, int $quantity = 1)
    {
        foreach ($this->cart as &$product) {
            if ($product["id"] == $productId) {
                $product["quantity"] += $quantity;
                $this->session->set(self::CART_SESSION, $this->cart);
                return;
            }
        }

        array_push($this->cart, [
            "id" => $productId,
            "quantity" => $quantity
        ]);
        $this->session->set(self::CART_SESSION, $this->cart);
    }

    public function decreaseProductQuantity(int $productId, int $quantity = 1)
    {
        foreach ($this->cart as &$cartProduct) {
            if ($cartProduct["id"] == $productId) {
                if ($cartProduct["quantity"] <= $quantity) {
                    return self::removeProduct($productId);
                } else {
                    $cartProduct["quantity"] -= $quantity;
                }
            }
        }

        $this->session->set(self::CART_SESSION, $this->cart);
    }

    public function removeProduct(int $productId)
    {
        foreach ($this->cart as &$cartProduct) {
            if ($cartProduct["id"] == $productId) {
                unset($cartProduct);
            }
        }
        $this->session->set(self::CART_SESSION, $this->cart);
    }

    public function reset()
    {
        $this->cart = array();
        $this->session->set(self::CART_SESSION, $this->cart);
    }
}
