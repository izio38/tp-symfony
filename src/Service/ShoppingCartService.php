<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\CommandLine;
use App\Entity\User;
use App\Repository\CommandLineRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ShoppingCartService
{
    const CART_SESSION = 'cart';
    private $session;
    private $shop;
    private $cart;

    private $mailer;
    private $templating;

    private $commandRepository;
    private $commandLineRepository;
    private $userRepository;
    private $productRepository;

    private $em;


    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $templating,
        SessionInterface $session,
        ShopService $shop,
        CommandRepository $commandRepository,
        CommandLineRepository $commandLineRepository,
        UserRepository $userRepository,
        ProductRepository $productRepository,
        EntityManagerInterface $em
    )
    {
        $this->shop = $shop;
        $this->session = $session;

        $this->mailer = $mailer;
        $this->templating = $templating;

        $this->cart = $session->get(self::CART_SESSION, array());

        $this->commandRepository = $commandRepository;
        $this->commandLineRepository = $commandLineRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;

        $this->em = $em;
    }

    public function getContent()
    {
        return $this->cart;
    }

    public function getExtendedContent()
    {
        if (!is_array($this->cart)) {
            return [];
        }
        return array_map(array($this, 'addExtendedInformation'), $this->cart);
    }

    public function addExtendedInformation($cartItem)
    {
        $product = $this->shop->findProductById($cartItem["id"]);
        $product->setQuantity($cartItem["quantity"]);
        return $product;
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;
        foreach ($this->cart as $cartProduct) {
            $product = $this->shop->findProductById($cartProduct["id"]);
            $totalPrice += $product->getPrice() * $cartProduct["quantity"];
        }
        return $totalPrice;
    }

    public function getProductNumber()
    {
        if (!is_array($this->cart)) {
            return 0;
        }
        return count($this->cart);
    }

    public function addProduct(int $productId, int $quantity = 1)
    {
        if (!is_array($this->cart)) {
            $this->cart = [];
        }
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
        foreach ($this->cart as $i => $cartProduct) {
            if ($cartProduct["id"] == $productId) {
                unset($this->cart[$i]);
            }
        }
        $this->session->set(self::CART_SESSION, $this->cart);
    }

    public function reset()
    {
        $this->cart = array();
        $this->session->set(self::CART_SESSION, $this->cart);
    }

    public function transformCartIntoAUserCommand(int $userId): Command
    {
        $command = new Command();

        foreach ($this->cart as $cartItem) {
            $commandLine = new CommandLine();

            $product = $this->productRepository->find($cartItem["id"]);

            $commandLine->setQuantity($cartItem["quantity"]);
            $commandLine->setPrice($product->getPrice());
            $commandLine->setCommand($command);
            $commandLine->setProduct($product);

            $command->addCommandLine($commandLine);
        }

        if (count($command->getCommandLines()) == 0) {
            return $command;
        }

        $command->setStatus("to-proceed");

        $user = $this->userRepository->find($userId);
        $command->setUser($user);

        $command->setCreatedAt(new \DateTime());

        $this->em->persist($command);

        $this->em->flush();

        self::reset();

        return $command;
    }

    public function notifyCommandSucceedEmail(Command $command) {
        $message = (new \Swift_Message('iBook - Commande effectuée avec succès'))
            ->setFrom(['romainbillot3009@gmail.com' => 'Romain de iBook'])
            ->setTo($command->getUser()->getEmail())
            ->setBody(
                $this->templating->render(
                    'emails/command-succeed.html.twig'
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
