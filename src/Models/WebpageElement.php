<?php

namespace Startupful\WebpageManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebpageElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeHeaders($query)
    {
        return $query->where('type', 'header');
    }

    public function scopeFooters($query)
    {
        return $query->where('type', 'footer');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}