<?php

namespace App\Twig;

use App\Service\CurrencyService;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    public $currencyService;
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function currencyConvert($number)
    {
        dump($this->currencyService->userCurrency);
        return $this->currencyService->getUserCurrency($number);
    }
}