<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\Eloquent\Collection;
use Mpyw\ComposhipsEagerLimit\Database\Eloquent\Relations\HasMany as MixedHasMany;

class HasManyTest extends TestCase
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
    }

    public function testLazyHasMany(): void
    {
        $this->assertJsonSame([$this->comments[0], $this->comments[1], $this->comments[2]], $this->posts[0]->comments);
        $this->assertJsonSame([$this->comments[3], $this->comments[4]], $this->posts[1]->comments);
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments);

        $this->assertJsonSame([$this->comments[1], $this->comments[2]], $this->posts[0]->authorComments);
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments);
        $this->assertJsonSame([], $this->posts[2]->authorComments);
    }

    public function testLazyHasManyLimitTwo(): void
    {
        $this->assertJsonSame([$this->comments[0], $this->comments[1]], $this->posts[0]->comments()->limit(2)->get());
        $this->assertJsonSame([$this->comments[3], $this->comments[4]], $this->posts[1]->comments()->limit(2)->get());
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments()->limit(2)->get());

        $this->assertJsonSame([$this->comments[1], $this->comments[2]], $this->posts[0]->authorComments()->limit(2)->get());
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments()->limit(2)->get());
        $this->assertJsonSame([], $this->posts[2]->authorComments()->limit(2)->get());
    }

    public function testLazyHasManyLimitOne(): void
    {
        $this->assertJsonSame([$this->comments[0]], $this->posts[0]->comments()->limit(1)->get());
        $this->assertJsonSame([$this->comments[3]], $this->posts[1]->comments()->limit(1)->get());
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments()->limit(1)->get());

        $this->assertJsonSame([$this->comments[1]], $this->posts[0]->authorComments()->limit(1)->get());
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments()->limit(1)->get());
        $this->assertJsonSame([], $this->posts[2]->authorComments()->limit(1)->get());
    }

    public function testEagerHasMany(): void
    {
        Collection::make($this->posts)->load('comments', 'authorComments');

        $this->assertJsonSame([$this->comments[0], $this->comments[1], $this->comments[2]], $this->posts[0]->comments);
        $this->assertJsonSame([$this->comments[3], $this->comments[4]], $this->posts[1]->comments);
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments);

        $this->assertJsonSame([$this->comments[1], $this->comments[2]], $this->posts[0]->authorComments);
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments);
        $this->assertJsonSame([], $this->posts[2]->authorComments);
    }

    public function testEagerHasManyLimitTwo(): void
    {
        Collection::make($this->posts)->load([
            'comments' => function (MixedHasMany $query) {
                $query->limit(2);
            },
            'authorComments' => function (MixedHasMany $query) {
                $query->limit(2);
            },
        ]);

        $this->assertJsonSame([$this->comments[0], $this->comments[1]], $this->posts[0]->comments);
        $this->assertJsonSame([$this->comments[3], $this->comments[4]], $this->posts[1]->comments);
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments);

        $this->assertJsonSame([$this->comments[1], $this->comments[2]], $this->posts[0]->authorComments);
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments);
        $this->assertJsonSame([], $this->posts[2]->authorComments);
    }

    public function testEagerHasManyLimitOne(): void
    {
        Collection::make($this->posts)->load([
            'comments' => function (MixedHasMany $query) {
                $query->limit(1);
            },
            'authorComments' => function (MixedHasMany $query) {
                $query->limit(1);
            },
        ]);

        $this->assertJsonSame([$this->comments[0]], $this->posts[0]->comments);
        $this->assertJsonSame([$this->comments[3]], $this->posts[1]->comments);
        $this->assertJsonSame([$this->comments[5]], $this->posts[2]->comments);

        $this->assertJsonSame([$this->comments[1]], $this->posts[0]->authorComments);
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->authorComments);
        $this->assertJsonSame([], $this->posts[2]->authorComments);
    }

    public function testEagerHasManyLimitOneWithOffset(): void
    {
        Collection::make($this->posts)->load([
            'comments' => function (MixedHasMany $query) {
                $query->limit(1)->offset(1);
            },
            'authorComments' => function (MixedHasMany $query) {
                $query->limit(1)->offset(1);
            },
        ]);

        $this->assertJsonSame([$this->comments[1]], $this->posts[0]->comments);
        $this->assertJsonSame([$this->comments[4]], $this->posts[1]->comments);
        $this->assertJsonSame([], $this->posts[2]->comments);

        $this->assertJsonSame([$this->comments[2]], $this->posts[0]->authorComments);
        $this->assertJsonSame([], $this->posts[1]->authorComments);
        $this->assertJsonSame([], $this->posts[2]->authorComments);
    }
}
