<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyzedString extends Model
{
      protected $table = 'strings';
    protected $fillable = [
        'value',
        'sha256_hash',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}

