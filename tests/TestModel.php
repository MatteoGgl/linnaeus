<?php

namespace MatteoGgl\Linnaeus\Tests;

use Illuminate\Database\Eloquent\Model;
use MatteoGgl\Linnaeus\HasSlug;
use MatteoGgl\Linnaeus\LinnaeusOptions;

class TestModel extends Model
{
    use HasSlug;

    protected $guarded = [];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->linnaeus = LinnaeusOptions::create()
            ->generatesOnUpdate();
    }
}
