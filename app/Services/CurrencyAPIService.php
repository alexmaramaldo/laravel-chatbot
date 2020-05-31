<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * CurrencyAPIService have the main logic to connect the AMDOREN API
 */
class CurrencyAPIService
{
    private $url_base;
    private $api_key;

    /**
     * Create a new CurrencyAPIService instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->url_base = getenv("AMDOREN_API_URL", "");
        $this->api_key = getenv("AMDOREN_API_SECRET", false);
    }

    /**
     * Show all currencies allowed, this method use a cache to return the result
     * avoiding for 8 hours in the API query again
     *
     * @return array
     */
    public function currencyList(): array
    {
        if (!$this->api_key) {
            throw new Exception("Secret token from AMDOREN is EMPTY", 400);
        }

        $data = [];
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

    /**
     * Convert value from a currency to a specific currency
     *
     * @param string $from Currency initial
     * @param string $to New Currency
     * @param float $amount Value to convert
     *
     * @return float the value converted
     */
    public function convertAmount(string $from, string $to, float $amount): float
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

    /**
     * Check if currency is allowed by application
     *
     * @param string $currency Currency initial
     *
     * @return bool
     */
    public function checkIfCurrencyExist(string $currency): bool
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
