<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnologyTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tech_type', 'user_id'];

    // リレーション
    public function collections(): belongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_technology');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ 検索
    public function scopeSearch($query, $searches)
    {
        if (!$searches || !is_array($searches)) {
            return $query;
        }

        // 🔹 フリー検索
        if (!empty($searches['name'])) {
            // 検索処理準備
            $search_split = mb_convert_kana($searches['name'], 's'); // 全角スペースを半角
            $search_split2 = preg_split('/[\s]+/', $search_split); //空白で区切る

            // フリー検索処理
            foreach ($search_split2 as $value) {
                $query->where('name', 'like', '%' . $value . '%');
            }
        }

        // 🔹 セレクトボックス検索
        foreach ($searches as $column => $value) {
            // フリー検索はもう処理したのでスキップ
            if ($column === 'name') {
                continue;
            }

            // セレクトボックス検索処理
            if (!is_null($value) && $value !== '') {
                $query->where($column, 'like', '%' . $value . '%');
            }
        }

        return $query;
    }
}
