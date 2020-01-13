<?php


namespace App\Service;


use http\Client\Request;
use Symfony\Component\HttpClient\HttpClient;

class CurrencyService
{
    const API_ENDPOINT = "https://api.exchangeratesapi.io/latest";
    const SUPPORTED_CURRENCIES = ["HRK", "JPY", "USD", "EUR"];
    const BASE = "EUR";

    public function __construct()
    {
        $client = HttpClient::create();
        $res = $client->request();
    }
}