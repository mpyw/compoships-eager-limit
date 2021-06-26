<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasMany as MixedHasMany;
use Staudenmeir\EloquentEagerLimit\Relations\BelongsToMany;

/**
 * Class Post
 *
 * @property Comment[]|\Illuminate\Database\Eloquent\Collection $comments
 * @property Comment[]|\Illuminate\Database\Eloquent\Collection $authorComments
 * @property \Illuminate\Database\Eloquent\Collection|Tag[]     $tags
 */
class Post extends Model
{
    use ComposhipsEagerLimit;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function comments(): MixedHasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function authorComments(): MixedHasMany
    {
        return $this->hasMany(Comment::class, ['post_id', 'user_id'], ['id', 'user_id']);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
