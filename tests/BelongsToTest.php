<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\Eloquent\Collection;

class BelongsToTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

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

    public function testLazyBelongsTo(): void
    {
        $this->assertJsonSame($this->posts[0], $this->comments[0]->post);
        $this->assertJsonSame($this->posts[0], $this->comments[1]->post);
        $this->assertJsonSame($this->posts[0], $this->comments[2]->post);
        $this->assertJsonSame($this->posts[1], $this->comments[3]->post);
        $this->assertJsonSame($this->posts[1], $this->comments[4]->post);
        $this->assertJsonSame($this->posts[2], $this->comments[5]->post);

        $this->assertJsonSame(null, $this->comments[0]->commenterPost);
        $this->assertJsonSame($this->posts[0], $this->comments[1]->commenterPost);
        $this->assertJsonSame($this->posts[0], $this->comments[2]->commenterPost);
        $this->assertJsonSame(null, $this->comments[3]->commenterPost);
        $this->assertJsonSame($this->posts[1], $this->comments[4]->commenterPost);
        $this->assertJsonSame(null, $this->comments[5]->commenterPost);
    }

    public function testEagerBelongsTo(): void
    {
        Collection::make($this->comments)->load('post', 'commenterPost');

        $this->assertJsonSame($this->posts[0], $this->comments[0]->post);
        $this->assertJsonSame($this->posts[0], $this->comments[1]->post);
        $this->assertJsonSame($this->posts[0], $this->comments[2]->post);
        $this->assertJsonSame($this->posts[1], $this->comments[3]->post);
        $this->assertJsonSame($this->posts[1], $this->comments[4]->post);
        $this->assertJsonSame($this->posts[2], $this->comments[5]->post);

        $this->assertJsonSame(null, $this->comments[0]->commenterPost);
        $this->assertJsonSame($this->posts[0], $this->comments[1]->commenterPost);
        $this->assertJsonSame($this->posts[0], $this->comments[2]->commenterPost);
        $this->assertJsonSame(null, $this->comments[3]->commenterPost);
        $this->assertJsonSame($this->posts[1], $this->comments[4]->commenterPost);
        $this->assertJsonSame(null, $this->comments[5]->commenterPost);
    }
}
