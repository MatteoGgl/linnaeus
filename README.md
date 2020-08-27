# Linnaeus

A package to create readable, random slugs for Eloquent models from animal names and adjectives.

## Installation

Just require the package through composer:

``` bash
composer require matteoggl/linneus
```

## Usage

Add the `HasSlug` trait on your Eloquent model and insert a column on it's table named `slug`, of type `string` with a
`unique` modifier.

``` php
$table->string('slug')->unique();
```

By default, a new unique string taken from a combination of 900~ adjectives, 100+ colors and 250~ animals is assigned to the model when 
created with the following structure: *adjective-adjective-animal*.

When updated, the slug will not be changed and if soft-deletable, the model's slug will be considered used.

The slugs are used with Laravel's implicit route model binding. For example, this code:
``` php
Route::get('/users/{user}', function (App\User $user) {
    return $user->email;
});
```
will use Linnaeus' slugs (e.g. `/users/moldy-encouraging-turtle`).

## Configuration

To publish the configuration file, run the following command:

``` php
php artisan vendor:publish --provider="MatteoGgl\Linnaeus\LinnaeusServiceProvider" --tag="config"
```

The `linnaeus.php` configuration file will appear in your `config/` directory.

## Per-model configuration

Some options can be overridden by updating them inside the model constructor, using a fluent API;
here's an example using all the available ones:

``` php
public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->linnaeus = LinnaeusOptions::create()
            ->withKey('linnaeus_slug')
            ->withStructure(['color', 'animal'])
            ->withSeparator('_')
            ->generatesOnUpdate()
            ->withInvalidAnimals(['aardvark'])
            ->withInvalidAdjectives(['zany'])
            ->withInvalidColors(['blue']);
    }
```

## FAQ

**Q: What's up with the name?**

A: Take a look at Carl Linnaeus on [Wikipedia](https://en.wikipedia.org/wiki/Carl_Linnaeus)

**Q: Can this package do *feature*?**

A: I created this package out of a need for a personal project. If you have some suggestions feel free to open an issue 
or a PR! Also, this package is heavily inspired from [spatie/laravel-sluggable](https://github.com/spatie/laravel-sluggable);
maybe the features you are looking for are there.