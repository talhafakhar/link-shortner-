<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShortUrl extends Model
{
    use HasFactory;
    protected $fillable = [
        'original_url',
        'slug',
        'expires_at',
        'visits',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function visits(): HasMany
    {
        return $this->hasMany(UrlVisit::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function incrementVisits(): void
    {
        $this->increment('visits');
    }
}
