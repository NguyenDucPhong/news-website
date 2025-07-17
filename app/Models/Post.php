<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_url',
        'status',
        'published_at',
        'category_id',
        'user_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category(): BelongsTo{
        return $this->belongsTo(Category::class);
    }
    public function tags(): BelongsToMany{
        return $this->belongsToMany(Tag::class);
    }
    public  function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }

    protected $casts = [
        'status' => PostStatus::class,
    ];
}
