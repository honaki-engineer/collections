<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    // リレーション
    public function collections(): belongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_technology');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 検索
    public function scopeSearch($query, $searches)
    {
        if ($searches !== null) {
            $search_split = mb_convert_kana($searches, 's'); // 全角スペースを半角
            $search_split2 = preg_split('/[\s]+/', $search_split); //空白で区切る
            foreach ($search_split2 as $value) {
                $query->where('name', 'like', '%' . $value . '%');
            }
        }

        return $query;
    }
}
