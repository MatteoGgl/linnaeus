<?php

namespace MatteoGgl\Linnaeus\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use MatteoGgl\Linnaeus\LinnaeusOptions;

class HasSlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_linnaeus_options()
    {
        $model = TestModel::create([
            'name' => 'Test'
        ]);

        $this->assertInstanceOf(LinnaeusOptions::class, $model->getLinnaeusOptions());
    }

    /** @test */
    public function it_generates_a_slug_on_creation()
    {
        $model = TestModel::create([
            'name' => 'Test'
        ]);

        $this->assertNotEmpty($model->slug);
    }

    /** @test */
    public function it_generates_a_different_slug_on_update()
    {
        config(['linnaeus.update' => true]);

        $model = TestModel::create([
            'name' => 'Test'
        ]);
        $slug = $model->slug;

        $model->update([
            'name' => 'Changed',
        ]);
        $model->refresh();

        $this->assertNotEquals($slug, $model->slug);
    }

    /** @test */
    public function it_generates_a_new_slug_if_duplicate()
    {
        config(['linnaeus.structure' => ['animal']]);

        $invalid_animals = Lang::get('linnaeus::animals');
        unset($invalid_animals['aardvark']);
        config(['linnaeus.invalid_animals' => $invalid_animals]);

        $aardvark = TestModel::create([
            'name' => 'Test'
        ]);
        $this->assertEquals('aardvark', $aardvark->slug);

        $invalid_animals = Lang::get('linnaeus::animals');
        unset($invalid_animals['albatross']);
        config(['linnaeus.invalid_animals' => $invalid_animals]);

        $albatross = TestModel::create([
            'name' => 'Test'
        ]);
        $this->assertEquals('albatross', $albatross->slug);
    }
}
