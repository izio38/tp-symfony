<?php


namespace App\Controller;


use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CurrencyController extends AbstractController
{
    public function change(CurrencyService $currencyService, Request $request, $to) {
        $currencyService->setUserCurrency($to);

        $request->getSession()
            ->getFlashBag();
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}