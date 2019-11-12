<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\Eloquent\Collection;
use Staudenmeir\EloquentEagerLimit\Relations\BelongsToMany;

class BelongsToManyTest extends TestCase
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

        $this->tags[0] = Tag::create()->fresh();
        $this->tags[1] = Tag::create()->fresh();
        $this->tags[2] = Tag::create()->fresh();

        $this->tags[0]->posts()->sync([$this->posts[0]->id, $this->posts[1]->id, $this->posts[2]->id]);
        $this->tags[1]->posts()->sync([$this->posts[1]->id, $this->posts[2]->id]);
        $this->tags[2]->posts()->sync([$this->posts[2]->id]);
    }

    public function testLazyBelongsToMany(): void
    {
        $this->assertJsonSame([$this->posts[0], $this->posts[1], $this->posts[2]], $this->tags[0]->posts);
        $this->assertJsonSame([$this->posts[1], $this->posts[2]], $this->tags[1]->posts);
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts);
    }

    public function testLazyBelongsToManyLimitTwo(): void
    {
        $this->assertJsonSame([$this->posts[0], $this->posts[1]], $this->tags[0]->posts()->limit(2)->orderBy('post_tag.post_id')->get());
        $this->assertJsonSame([$this->posts[1], $this->posts[2]], $this->tags[1]->posts()->limit(2)->orderBy('post_tag.post_id')->get());
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts()->limit(2)->orderBy('post_tag.post_id')->get());
    }

    public function testLazyBelongsToManyLimitOne(): void
    {
        $this->assertJsonSame([$this->posts[0]], $this->tags[0]->posts()->limit(1)->orderBy('post_tag.post_id')->get());
        $this->assertJsonSame([$this->posts[1]], $this->tags[1]->posts()->limit(1)->orderBy('post_tag.post_id')->get());
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts()->limit(1)->orderBy('post_tag.post_id')->get());
    }

    public function testEagerBelongsToMany(): void
    {
        Collection::make($this->tags)->load('posts');

        $this->assertJsonSame([$this->posts[0], $this->posts[1], $this->posts[2]], $this->tags[0]->posts);
        $this->assertJsonSame([$this->posts[1], $this->posts[2]], $this->tags[1]->posts);
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts);
    }

    public function testEagerBelongsToManyLimitTwo(): void
    {
        Collection::make($this->tags)->load([
            'posts' => function (BelongsToMany $query) {
                $query->limit(2)->orderBy('post_tag.post_id');
            },
        ]);

        $this->assertJsonSame([$this->posts[0], $this->posts[1]], $this->tags[0]->posts);
        $this->assertJsonSame([$this->posts[1], $this->posts[2]], $this->tags[1]->posts);
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts);
    }

    public function testEagerBelongsToManyLimitOne(): void
    {
        Collection::make($this->tags)->load([
            'posts' => function (BelongsToMany $query) {
                $query->limit(1)->orderBy('post_tag.post_id');
            },
        ]);

        $this->assertJsonSame([$this->posts[0]], $this->tags[0]->posts);
        $this->assertJsonSame([$this->posts[1]], $this->tags[1]->posts);
        $this->assertJsonSame([$this->posts[2]], $this->tags[2]->posts);
    }

    public function testEagerBelongsToManyLimitOneWithOffset(): void
    {
        Collection::make($this->tags)->load([
            'posts' => function (BelongsToMany $query) {
                $query->limit(1)->offset(1)->orderBy('post_tag.post_id');
            },
        ]);

        $this->assertJsonSame([$this->posts[1]], $this->tags[0]->posts);
        $this->assertJsonSame([$this->posts[2]], $this->tags[1]->posts);
        $this->assertJsonSame([], $this->tags[2]->posts);
    }
}
