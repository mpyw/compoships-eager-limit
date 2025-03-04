# Compoships Eager Limit [![Build Status](https://github.com/mpyw/compoships-eager-limit/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/mpyw/compoships-eager-limit/actions) [![Coverage Status](https://coveralls.io/repos/github/mpyw/compoships-eager-limit/badge.svg?branch=master)](https://coveralls.io/github/mpyw/compoships-eager-limit?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mpyw/compoships-eager-limit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mpyw/compoships-eager-limit/?branch=master)

[topclaudy/compoships](https://github.com/topclaudy/compoships) + [staudenmeir/eloquent-eager-limit](https://github.com/staudenmeir/eloquent-eager-limit)

> [!CAUTION]
> [staudenmeir/eloquent-eager-limit](https://github.com/staudenmeir/eloquent-eager-limit) has been merged into the core since Laravel 11. Therefore, it should have been enough to only install [topclaudy/compoships](https://github.com/topclaudy/compoships)... However, as of March 2025, PR [topclaudy/compoships#180](https://github.com/topclaudy/compoships/pull/180) has not yet been merged. Once we have seen this merged, we will abandon the package.

## Requirements

- PHP: `^8.0`
- Laravel: `^9.0 || ^10.0`
- [Compoships](https://github.com/topclaudy/compoships): `^2.0.4`
- [Eloquent Eager Limit](https://github.com/staudenmeir/eloquent-eager-limit): `^1.7.1`

## Installing

```
composer require mpyw/compoships-eager-limit
```

## Usage

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class Post extends Model
{
    use ComposhipsEagerLimit;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function authorComments()
    {
        return $this->hasMany(Comment::class, ['post_id', 'user_id'], ['id', 'user_id']);
    }
}
```

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mpyw\ComposhipsEagerLimit\ComposhipsEagerLimit;

class Comment extends Model
{
    use ComposhipsEagerLimit;
}
```

```php
$posts = Post::with(['authorComments' => function ($query) {
    $query->limit(3)->offset(1);
}])->get();
```

For more details, visit each base package repository.

- [topclaudy/compoships](https://github.com/topclaudy/compoships)
- [staudenmeir/eloquent-eager-limit](https://github.com/staudenmeir/eloquent-eager-limit)

## Special Thanks

- **[@topclaudy](https://github.com/topclaudy)**
- **[@staudenmeir](https://github.com/staudenmeir)**
