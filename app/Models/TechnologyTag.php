<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TechnologyTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // リレーション
    public function collections() : belongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_technology');
    }
}
