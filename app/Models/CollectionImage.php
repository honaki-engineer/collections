<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Collection;

class CollectionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'image_path',
        'position',
    ];

    // リレーション
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
