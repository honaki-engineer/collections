<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $fillable = [
        'title',
        'description',
        'url_qiita',
        'url_webapp',
        'url_github',
        'is_public',
        'position',
        'user_id',
    ];

    // リレーション
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function collection_image(): HasMany
    {
        return $this->hasMany(CollectionImage::class, 'collection_id');
    }
    public function technologyTags(): BelongsToMany
    {
        return $this->belongsToMany(TechnologyTag::class, 'collection_technology');
    }

    // 検索
    public function scopeSearch($query, $searches)
    {
        foreach ($searches as $column => $value) {
            if ($value !== null) { 
                $query->where($column, 'like', '%' . $value . '%');
            }
        }
        return $query;
    }
}
