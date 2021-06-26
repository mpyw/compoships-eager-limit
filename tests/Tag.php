<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Staudenmeir\EloquentEagerLimit\Relations\BelongsToMany;

/**
 * Class Tag
 *
 * @property \Illuminate\Database\Eloquent\Collection|Post[] $posts
 */
class Tag extends Model
{
    use ComposhipsEagerLimit;

    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
