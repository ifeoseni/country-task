<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Country extends Model
{
    protected $fillable = [
        'name',
        'capital',
        'region',
        'population',
        'currency_code',
        'exchange_rate',
        'estimated_gdp',
        'flag_url',
        'last_refreshed_at',
    ];

    protected $casts = [
        'population' => 'integer',
        'exchange_rate' => 'decimal:6',
        'estimated_gdp' => 'decimal:6',
        'last_refreshed_at' => 'datetime',
    ];

    /**
     * Validation rules for creating/updating countries
     * UPDATED TO MATCH BUSINESS LOGIC REQUIREMENTS
     */
    public static function validationRules($forUpdate = false): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'capital' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'population' => 'required|integer|min:0',
            'currency_code' => 'nullable|string|max:10', // CHANGED: can be null
            'exchange_rate' => 'nullable|numeric', // CHANGED: can be null, no min
            'estimated_gdp' => 'nullable|numeric', // CHANGED: can be null, no min
            'flag_url' => 'nullable|url|max:500',
            'last_refreshed_at' => 'nullable|date',
        ];

        if ($forUpdate) {
            $rules['name'] = 'sometimes|'.$rules['name'];
            $rules['population'] = 'sometimes|'.$rules['population'];
            $rules['currency_code'] = 'sometimes|'.$rules['currency_code'];
            $rules['exchange_rate'] = 'sometimes|'.$rules['exchange_rate'];
            $rules['estimated_gdp'] = 'sometimes|'.$rules['estimated_gdp'];
        }

        return $rules;
    }

    /**
     * Scope for filtering by region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope for filtering by currency code
     */
    public function scopeByCurrency($query, $currencyCode)
    {
        return $query->where('currency_code', $currencyCode);
    }

    /**
     * Scope for sorting by GDP - FIXED to handle null values
     */
    public function scopeSortByGdp($query, $direction = 'desc')
    {
        // Handle null values by putting them at the end
        if ($direction === 'desc') {
            return $query->orderByRaw('estimated_gdp IS NULL, estimated_gdp DESC');
        } else {
            return $query->orderByRaw('estimated_gdp IS NULL, estimated_gdp ASC');
        }
    }

    /**
     * Scope for sorting by population
     */
    public function scopeSortByPopulation($query, $direction = 'desc')
    {
        return $query->orderBy('population', $direction);
    }

    /**
     * Scope for sorting by name
     */
    public function scopeSortByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }

    /**
     * Get the top countries by GDP - FIXED to handle sorting properly
     */
    public static function getTopByGdp($limit = 5)
    {
        return static::whereNotNull('estimated_gdp')
                    ->orderByRaw('CAST(estimated_gdp AS DECIMAL(30,6)) DESC')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Calculate estimated GDP based on population and exchange rate
     */
    public static function calculateEstimatedGdp($population, $exchangeRate): float
    {
        if (!$exchangeRate || $exchangeRate <= 0) {
            return 0;
        }

        $randomMultiplier = rand(1000, 2000);
        return (float) (($population * $randomMultiplier) / $exchangeRate);
    }
}