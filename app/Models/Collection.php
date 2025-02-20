<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url_qiita',
        'url_webapp',
        'url_github',
        'is_public',
        'position',
    ];
}
