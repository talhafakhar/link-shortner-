<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrlVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_url_id',
        'ip_address',
    ];

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
