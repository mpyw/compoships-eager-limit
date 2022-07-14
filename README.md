# Compoships Eager Limit [![Build Status](https://github.com/mpyw/compoships-eager-limit/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/mpyw/compoships-eager-limit/actions) [![Coverage Status](https://coveralls.io/repos/github/mpyw/compoships-eager-limit/badge.svg?branch=master)](https://coveralls.io/github/mpyw/compoships-eager-limit?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mpyw/compoships-eager-limit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mpyw/compoships-eager-limit/?branch=master)

[topclaudy/compoships](https://github.com/topclaudy/compoships) + [staudenmeir/eloquent-eager-limit](https://github.com/staudenmeir/eloquent-eager-limit)

## Requirements

- PHP: `^7.3 || ^8.0`
- Laravel: `^6.0 || ^7.0 || ^8.0 || ^9.0`
- [Compoships](https://github.com/topclaudy/compoships): `^2.0.4`
- [Eloquent Eager Limit](https://github.com/staudenmeir/eloquent-eager-limit): `^1.4`

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
