<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CurrencyService
{
    const API_ENDPOINT = "https://api.exchangeratesapi.io/latest";
    const SUPPORTED_CURRENCIES = ["HRK", "JPY", "USD"];
    const CURRENCY_SESSION = 'currency';

    public $rates = [
        "EUR" => 1,
    ];
    public $userCurrency = "EUR";
    public $userSession;

    public function __construct(SessionInterface $session)
    {
        $this->userSession = $session;
        $this->initCurrencies();
        $this->userCurrency = $session->get(self::CURRENCY_SESSION, "EUR");
    }

    private function initCurrencies()
    {
        $client = HttpClient::create();

        $res = $client->request("GET", self::API_ENDPOINT)->toArray();

        foreach ($res["rates"] as $currency => $rate) {
            foreach (self::SUPPORTED_CURRENCIES as $SUPPORTED_CURRENCY) {
                if ($SUPPORTED_CURRENCY == $currency) {
                    $this->rates[$currency] = $rate;
                }
            }
        }
    }

    public function getUserCurrency($baseAmount) {
        return $this->toCurrency($baseAmount, $this->userCurrency);
    }

    public function getStringCurrency() {
        return $this->userCurrency;
    }

    public function setUserCurrency($currency) {
        $this->userCurrency = $currency;
        $this->userSession->set(self::CURRENCY_SESSION, $this->userCurrency);
    }

    private function toCurrency($baseAmount, $currency) {
        return $baseAmount * ( $this->rates[$currency] );
    }
}