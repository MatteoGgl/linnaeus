<?php

namespace MatteoGgl\Linnaeus\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use MatteoGgl\Linnaeus\LinnaeusOptions;

class LinnaeusOptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_defaults()
    {
        $data = LinnaeusOptions::create();

        $this->assertEquals('slug', $data->slug_key);
        $this->assertEquals(['adjective', 'adjective', 'animal'], $data->structure);
        $this->assertEquals('-', $data->separator);
        $this->assertTrue($data->create);
        $this->assertFalse($data->update);
        $this->assertNotEmpty($data->getLists());
        $this->assertCount(2, $data->getLists());
        foreach ($data->getLists() as $list) {
            $this->assertNotEmpty($list);
        }
        $this->assertEmpty($data->invalid_animals);
        $this->assertEmpty($data->invalid_adjectives);
        $this->assertEmpty($data->invalid_colors);
    }

    /** @test */
    public function it_has_defaults_when_missing_config()
    {
        config(['linnaeus' => []]);

        $data = LinnaeusOptions::create();

        $this->assertEquals('slug', $data->slug_key);
        $this->assertEquals(['adjective', 'adjective', 'animal'], $data->structure);
        $this->assertEquals('-', $data->separator);
        $this->assertTrue($data->create);
        $this->assertFalse($data->update);
        $this->assertNotEmpty($data->getLists());
        $this->assertCount(2, $data->getLists());
        foreach ($data->getLists() as $list) {
            $this->assertNotEmpty($list);
        }
        $this->assertEmpty($data->invalid_animals);
        $this->assertEmpty($data->invalid_adjectives);
        $this->assertEmpty($data->invalid_colors);
    }

    /** @test */
    public function it_sets_the_key()
    {
        $data = LinnaeusOptions::create()
            ->withKey('linnaeus_slug');

        $this->assertEquals('linnaeus_slug', $data->slug_key);
    }

    /** @test */
    public function it_sets_the_structure()
    {
        $structure = [
            'color',
            'adjective',
            'animal'
        ];

        $data = LinnaeusOptions::create()
            ->withStructure($structure);

        $this->assertEquals($structure, $data->structure);
    }

    /** @test */
    public function it_sets_the_separator()
    {
        $data = LinnaeusOptions::create()
            ->withSeparator('_');

        $this->assertEquals('_', $data->separator);
    }

    /** @test */
    public function it_sets_generates_on_update()
    {
        $data = LinnaeusOptions::create()
            ->generatesOnUpdate();

        $this->assertTrue($data->update);
    }

    /** @test */
    public function it_sets_invalid_animals()
    {
        $data = LinnaeusOptions::create()
            ->withInvalidAnimals(['aardvark']);

        $this->assertEquals(['aardvark'], $data->invalid_animals);
    }

    /** @test */
    public function it_sets_invalid_adjectives()
    {
        $data = LinnaeusOptions::create()
            ->withInvalidAdjectives(['zany']);

        $this->assertEquals(['zany'], $data->invalid_adjectives);
    }

    /** @test */
    public function it_sets_invalid_colors()
    {
        $data = LinnaeusOptions::create()
            ->withInvalidColors(['blue']);

        $this->assertEquals(['blue'], $data->invalid_colors);
    }

    /** @test */
    public function it_updates_the_lists()
    {
        $data = LinnaeusOptions::create();
        $this->assertCount(2, $data->getLists());

        $data->withStructure(['color', 'adjective', 'adjective', 'animal']);
        $this->assertCount(3, $data->getLists());
    }

    /** @test */
    public function it_populates_animals_list_correctly()
    {
        $invalid_animals = Lang::get('linnaeus::animals');
        unset($invalid_animals['aardvark']);
        config(['linnaeus.invalid_animals' => $invalid_animals]);

        $data = LinnaeusOptions::create()
            ->withStructure(['animal']);

        $this->assertCount(1, $data->getList('animals'));
        $this->assertEquals(['aardvark' => 'Aardvark'], $data->getList('animals'));
    }

    /** @test */
    public function it_populates_adjectives_list_correctly()
    {
        $invalid_adjectives = Lang::get('linnaeus::adjectives');
        unset($invalid_adjectives['zany']);
        config(['linnaeus.invalid_adjectives' => $invalid_adjectives]);

        $data = LinnaeusOptions::create()
            ->withStructure(['adjective']);

        $this->assertCount(1, $data->getList('adjectives'));
        $this->assertEquals(['zany' => 'Zany'], $data->getList('adjectives'));
    }

    /** @test */
    public function it_populates_colors_list_correctly()
    {
        $invalid_colors = Lang::get('linnaeus::colors');
        unset($invalid_colors['blue']);
        config(['linnaeus.invalid_colors' => $invalid_colors]);

        $data = LinnaeusOptions::create()
            ->withStructure(['color']);

        $this->assertCount(1, $data->getList('colors'));
        $this->assertEquals(['blue' => 'Blue'], $data->getList('colors'));
    }

    /** @test */
    public function it_lazy_loads_the_lists()
    {
        $data = LinnaeusOptions::create();

        $this->assertEmpty($data->getRawLists());

        $data->getList('animals');

        $this->assertNotEmpty($data->getRawLists());
        $this->assertCount(2, $data->getRawLists());
    }
}
