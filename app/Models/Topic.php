<?php

namespace App\Models;

use App\Enums\PostType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'board_id',
        'user_id',
        'title',
        'description',
        'slug',
        'status',
        'is_pinned',
        'is_locked',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'status' => \App\Enums\TopicStatus::class,
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
        ];
    }

    public function board(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class)->where('type', PostType::Reply);
    }

    public function latestPost()
    {
        return $this->hasOne(Post::class)->latest('posts.created_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
