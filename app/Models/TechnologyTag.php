<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnologyTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'tech_type'
    ];

    // リレーション
    public function collections() : belongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_technology');
    }
    public function user() : BelongsTo
    {
    return $this->belongsTo(User::class);
    }
}
