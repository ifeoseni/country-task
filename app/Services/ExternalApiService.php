<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    /**
     * Fetch exchange rates from ER API
     */
    public function fetchExchangeRates(): array
    {
        try {
            $url = 'https://open.er-api.com/v6/latest/USD';
            
            Log::info("Fetching exchange rates from: {$url}");
            
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                Log::error('Exchange Rates API failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to fetch exchange rates: ' . $response->status());
            }
            
            $data = $response->json();
            
            // Debug: log the response structure
            Log::info('Exchange API response keys', array_keys($data));
            
            if (!isset($data['rates']) || !is_array($data['rates'])) {
                Log::error('Exchange Rates API returned invalid structure', [
                    'response_keys' => array_keys($data),
                    'rates_set' => isset($data['rates']),
                    'rates_type' => gettype($data['rates'] ?? 'not set')
                ]);
                throw new Exception('Invalid data format from exchange rates API - missing rates array');
            }
            
            $rates = $data['rates'];
            Log::info('Successfully fetched exchange rates', [
                'currencies_count' => count($rates),
                'sample_rates' => array_slice($rates, 0, 5, true)
            ]);
            
            return $rates;
            
        } catch (Exception $e) {
            Log::error('Exchange Rates API exception: ' . $e->getMessage());
            throw new Exception('Could not fetch data from Exchange Rates API: ' . $e->getMessage());
        }
    }

    /**
     * Fetch countries data from REST Countries API
     */
    public function fetchCountries(): array
    {
        try {
            $url = 'https://restcountries.com/v2/all?fields=name,capital,region,population,flag,currencies';
            
            Log::info("Fetching countries from: {$url}");
            
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                Log::error('Countries API failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new Exception('Failed to fetch countries data: ' . $response->status());
            }
            
            $data = $response->json();
            
            if (!is_array($data)) {
                Log::error('Countries API returned invalid data type', [
                    'type' => gettype($data)
                ]);
                throw new Exception('Invalid data format from countries API');
            }
            
            Log::info('Successfully fetched countries data', ['count' => count($data)]);
            return $data;
            
        } catch (Exception $e) {
            Log::error('Countries API exception: ' . $e->getMessage());
            throw new Exception('Could not fetch data from Countries API: ' . $e->getMessage());
        }
    }
}