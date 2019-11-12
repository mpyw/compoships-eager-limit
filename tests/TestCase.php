<?php

namespace Mpyw\ComposhipsEagerLimit\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var User[]
     */
    protected $users;

    /**
     * @var Post[]
     */
    protected $posts;

    /**
     * @var Tag[]
     */
    protected $tags;

    /**
     * @var Comment[]
     */
    protected $comments;

    /**
     * @var Approval[]
     */
    protected $approvals;

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [];
    }

    public function setUp(): void
    {
        parent::setUp();

        config(['database.connections' => require __DIR__ . '/config/database.php']);
        config(['database.default' => getenv('DB') ?: 'sqlite']);

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('tags');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('comment_id');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->timestamps();
        });
    }

    public function tearDown(): void
    {
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    protected function assertJsonSame($expected, $actual): void
    {
        $this->assertSame(
            json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
