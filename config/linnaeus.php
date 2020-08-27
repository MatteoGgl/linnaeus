<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Slug Key
    |--------------------------------------------------------------------------
    | The model's attribute and column name to use for slug generation and
    | route model binding.
    |
    | If you change it, remember to update the corresponding migration.
    |
    */
    'key' => 'slug',


    /*
    |--------------------------------------------------------------------------
    | Slug Structure
    |--------------------------------------------------------------------------
    | The structure of the randomly generated string that composes the slug.
    | The possible values in the array are:
    | - adjective
    | - animal
    | - color
    | This is a future-proofing option for when translations will be available and
    | the language grammatical rules dictate a different order for the elements.
    |
    | It is *much* recommended to leave at least two adjectives in the array,
    | since it greatly increases the possible generated string space.
    |
    */
    'structure' => [
        'adjective',
        'adjective',
        'animal',
    ],


    /*
    |--------------------------------------------------------------------------
    | Slug Separator
    |--------------------------------------------------------------------------
    | The slug generation internally uses Str::slug() to provide the final string.
    | The separator is passed to the function as-is, and can be changed to your
    | personal preference.
    |
    */
    'separator' => '-',


    /*
    |--------------------------------------------------------------------------
    | Create slug on model's create
    |--------------------------------------------------------------------------
    | Whether to create the slug during the model's creation.
    |
    | Internally, the model's `creating` event is used.
    |
    */
    'create' => true,

    /*
    |--------------------------------------------------------------------------
    | Update slug on model's update
    |--------------------------------------------------------------------------
    | Whether to update the slug when updating the model.
    |
    | Internally, the model's `updating` event is used.
    |
    */
    'update' => false,


    /*
    |--------------------------------------------------------------------------
    | Invalid values
    |--------------------------------------------------------------------------
    | Each of these arrays can contain the translation keys for the respective
    | slug element: they will not be used in the random generation process.
    | To examine the keys, first you'll need to publish the translations with:
    |
    | php artisan vendor:publish --provider="MatteoGgl\Linnaeus\LinnaeusServiceProvider" --tag="translations"
    |
    | This is mainly used for testing purposes, but still is available to use.
    |
    */
    'invalid_animals' => [],
    'invalid_adjectives' => [],
    'invalid_colors' => [],
];