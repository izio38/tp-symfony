<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCardController extends AbstractController
{

    public function index()
    {
        return $this->render('shopping_card/index.html.twig', [
            'controller_name' => 'ShoppingCardController',
        ]);
    }
}
