<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/**
 * App\Models\Collection
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $url_qiita
 * @property string|null $url_webapp
 * @property string|null $url_github
 * @property int $is_public
 * @property int $position
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUrlGithub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUrlQiita($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUrlWebapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUserId($value)
 * @mixin \Eloquent
 */
class Collection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'url_qiita', 'url_webapp', 'url_github', 'is_public', 'position', 'user_id', 'private_memo'];

    // ✅ リレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function collectionImages(): HasMany
    {
        return $this->hasMany(CollectionImage::class, 'collection_id');
    }
    public function technologyTags(): BelongsToMany
    {
        return $this->belongsToMany(TechnologyTag::class, 'collection_technology')
            ->withPivot('position');
    }
    public function featureTags(): BelongsToMany
    {
        return $this->belongsToMany(FeatureTag::class, 'collection_feature')
            ->withPivot('position');
    }

    // ✅ AdminIndex検索
    public function scopeSearchAdminIndex($query, $searches)
    {
        foreach ($searches as $column => $value) {
            if ($value !== null) {
                $query->where($column, 'like', '%' . $value . '%');
            }
        }
        return $query;
    }

    // ✅ PublicSiteIndexの検索
    public function scopeSearch($query, $searches)
    {
        foreach ($searches as $column => $value) {
            // セレクトボックス検索処理
            if (!is_null($value) && $value !== '') {
                // 技術タグ
                // collectionsの中で、特定のtechnology_tag_idを持つものだけに絞りたいから、whereHas()を使って、中間テーブル越しに検索している
                if ($column === 'technology_tag_id') {
                    // $query->whereHasの検索結果
                    // ↓ $query = collectionsテーブルに対するクエリビルダ
                    // ↓ function($q) = 無名関数(関数名がない = 簡単な処理で使う)
                    // ↓ $q  = technologyTagsテーブルに対するクエリビルダ(検索構築マシン)のインスタンス
                    // ↓ use($value) = 無名関数の外にある変数($value)を中で使うための仕組み。
                    $query->whereHas('technologyTags', function ($q) use ($value) {
                        $q->where('technology_tags.id', $value);
                    });
                }
                // 機能タグ
                if ($column === 'feature_tag_id') {
                    $query->whereHas('featureTags', function ($q) use ($value) {
                        $q->where('feature_tags.id', $value);
                    });
                }
            }
        }
        return $query;
    }

    // ✅ モデルのレコードが削除されるときに、関連画像ファイルも削除
    protected static function boot()
    {
        // 「特定のタイミングで自動的に処理を実行する仕組み」 = ライフサイクルイベント（イベントフック） → それらを設定するのがboot()メソッド
        // 🔹 creating、updating使用時に必須
        parent::boot();

        //  コレクションが削除される直前に、関連する画像ファイルも一緒に削除する処理
        static::deleting(function ($collection) {
            // Collectionモデルの**削除イベント(deleting)**にフック
            foreach ($collection->collectionImages as $image) {
                Storage::disk('public')->delete('collection_images/' . $image->image_path); // ファイル削除（storage/app/public/collection_images）
            }
        });
    }
}
