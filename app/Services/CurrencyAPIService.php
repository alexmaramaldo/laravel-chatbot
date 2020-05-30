<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class CurrencyAPIService
{
    private $url_base;
    private $api_key;

    public function __construct()
    {
        $this->url_base = getenv("AMDOREN_API_URL", "");
        $this->api_key = getenv("AMDOREN_API_SECRET", false);
    }

    public function currencyList()
    {
        if (!$this->api_key) {
            throw new Exception("Secret token from AMDOREN is EMPTY", 400);
        }

        if (Cache::has('currency_list')) {
            $data = Cache::get('currency_list');
        } else {
            $response = Http::get($this->url_base . "/currency_list.php", [
                'api_key' => $this->api_key
            ]);

            if (!$response->ok()) {
                throw new Exception("Could not connect to the AMDOREN API", 400);
            }

            $currencies = $response->json()['currencies'];

            $data = Cache::remember('currency_list', 3600 * 24, function () use ($currencies) {
                return $currencies;
            });
        }

        return $data;
    }

    public function convertAmount(string $from, $to, $amount)
    {
        if (!$this->api_key) {
            throw new Exception("Secret token from AMDOREN is EMPTY", 400);
        }

        $response = Http::get($this->url_base . "/currency.php", [
            'api_key' => $this->api_key,
            'from' => $from,
            'to' => $to,
            'amount' => $amount
        ]);

        if (!$response->ok()) {
            throw new Exception("Could not connect to the AMDOREN API", 400);
        }

        $amount = $response->json()['amount'];

        return number_format($amount, 2);
    }

    public function checkIfCurrencyExist($currency)
    {
        $currencies = $this->currencyList();
        $key = array_search($currency, array_column($currencies, 'currency'));

        if ($key > 0) {
            return true;
        } else {
            return false;
        }
    }
}
