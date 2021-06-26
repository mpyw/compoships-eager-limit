<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo as ComposhipsBelongsTo;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasOne as MixedHasOne;

/**
 * Class Comment
 *
 * @property null|Post     $post
 * @property null|Post     $commenterPost
 * @property null|Approval $approval
 * @property null|Approval $selfApproval
 */
class Comment extends Model
{
    use ComposhipsEagerLimit;

    protected $guarded = [];

    public function post(): ComposhipsBelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function commenterPost(): ComposhipsBelongsTo
    {
        return $this->belongsTo(Post::class, ['post_id', 'user_id'], ['id', 'user_id']);
    }

    public function approval(): MixedHasOne
    {
        return $this->hasOne(Approval::class);
    }

    public function selfApproval(): MixedHasOne
    {
        return $this->hasOne(Approval::class, ['comment_id', 'user_id'], ['id', 'user_id']);
    }
}
