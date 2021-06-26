<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasOne as MixedHasOne;

class HasOneTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2020-01-01 00:00:00');

        $this->users[0] = User::create()->fresh();
        $this->users[1] = User::create()->fresh();

        $this->posts[0] = Post::create(['user_id' => $this->users[0]->id])->fresh();
        $this->comments[0] = Comment::create(['user_id' => $this->users[1]->id, 'post_id' => $this->posts[0]->id])->fresh();
        $this->comments[1] = Comment::create(['user_id' => $this->users[0]->id, 'post_id' => $this->posts[0]->id])->fresh();
        $this->comments[2] = Comment::create(['user_id' => $this->users[0]->id, 'post_id' => $this->posts[0]->id])->fresh();

        $this->posts[1] = Post::create(['user_id' => $this->users[1]->id])->fresh();
        $this->comments[3] = Comment::create(['user_id' => $this->users[0]->id, 'post_id' => $this->posts[1]->id])->fresh();
        $this->comments[4] = Comment::create(['user_id' => $this->users[1]->id, 'post_id' => $this->posts[1]->id])->fresh();

        $this->posts[2] = Post::create(['user_id' => $this->users[1]->id])->fresh();
        $this->comments[5] = Comment::create(['user_id' => $this->posts[0]->id, 'post_id' => $this->posts[2]->id])->fresh();

        $this->approvals[0] = Approval::create(['user_id' => $this->users[0]->id, 'comment_id' => $this->comments[0]->id])->fresh();
        $this->approvals[1] = Approval::create(['user_id' => $this->users[1]->id, 'comment_id' => $this->comments[2]->id])->fresh();
        $this->approvals[2] = Approval::create(['user_id' => $this->users[1]->id, 'comment_id' => $this->comments[4]->id])->fresh();
        $this->approvals[3] = Approval::create(['user_id' => $this->users[1]->id, 'comment_id' => $this->comments[5]->id])->fresh();
    }

    public function testLazyHasOne(): void
    {
        $this->assertJsonSame($this->approvals[0], $this->comments[0]->approval);
        $this->assertJsonSame($this->approvals[1], $this->comments[2]->approval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->approval);
        $this->assertJsonSame($this->approvals[3], $this->comments[5]->approval);

        $this->assertJsonSame(null, $this->comments[0]->selfApproval);
        $this->assertJsonSame(null, $this->comments[2]->selfApproval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->selfApproval);
        $this->assertJsonSame(null, $this->comments[5]->selfApproval);
    }

    public function testLazyHasOneLimit(): void
    {
        $this->assertJsonSame($this->approvals[0], $this->comments[0]->approval()->limit(1)->first());
        $this->assertJsonSame($this->approvals[1], $this->comments[2]->approval()->limit(1)->first());
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->approval()->limit(1)->first());
        $this->assertJsonSame($this->approvals[3], $this->comments[5]->approval()->limit(1)->first());

        $this->assertJsonSame(null, $this->comments[0]->selfApproval()->limit(1)->first());
        $this->assertJsonSame(null, $this->comments[2]->selfApproval()->limit(1)->first());
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->selfApproval()->limit(1)->first());
        $this->assertJsonSame(null, $this->comments[5]->selfApproval()->limit(1)->first());
    }

    public function testEagerHasOne(): void
    {
        Collection::make($this->comments)->load('approval', 'selfApproval');

        $this->assertJsonSame($this->approvals[0], $this->comments[0]->approval);
        $this->assertJsonSame($this->approvals[1], $this->comments[2]->approval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->approval);
        $this->assertJsonSame($this->approvals[3], $this->comments[5]->approval);

        $this->assertJsonSame(null, $this->comments[0]->selfApproval);
        $this->assertJsonSame(null, $this->comments[2]->selfApproval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->selfApproval);
        $this->assertJsonSame(null, $this->comments[5]->selfApproval);
    }

    public function testEagerHasOneLimit(): void
    {
        Collection::make($this->comments)->load([
            'approval' => function (MixedHasOne $query) {
                $query->limit(1);
            },
            'selfApproval' => function (MixedHasOne $query) {
                $query->limit(1);
            },
        ]);

        $this->assertJsonSame($this->approvals[0], $this->comments[0]->approval);
        $this->assertJsonSame($this->approvals[1], $this->comments[2]->approval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->approval);
        $this->assertJsonSame($this->approvals[3], $this->comments[5]->approval);

        $this->assertJsonSame(null, $this->comments[0]->selfApproval);
        $this->assertJsonSame(null, $this->comments[2]->selfApproval);
        $this->assertJsonSame($this->approvals[2], $this->comments[4]->selfApproval);
        $this->assertJsonSame(null, $this->comments[5]->selfApproval);
    }

    public function testEagerHasOneLimitWithOffset(): void
    {
        Collection::make($this->comments)->load([
            'approval' => function (MixedHasOne $query) {
                $query->limit(1)->offset(1);
            },
            'selfApproval' => function (MixedHasOne $query) {
                $query->limit(1)->offset(1);
            },
        ]);

        $this->assertJsonSame(null, $this->comments[0]->approval);
        $this->assertJsonSame(null, $this->comments[2]->approval);
        $this->assertJsonSame(null, $this->comments[4]->approval);
        $this->assertJsonSame(null, $this->comments[5]->approval);

        $this->assertJsonSame(null, $this->comments[0]->selfApproval);
        $this->assertJsonSame(null, $this->comments[2]->selfApproval);
        $this->assertJsonSame(null, $this->comments[4]->selfApproval);
        $this->assertJsonSame(null, $this->comments[5]->selfApproval);
    }
}
