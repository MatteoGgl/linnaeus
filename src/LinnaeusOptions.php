<?php

namespace MatteoGgl\Linnaeus;

use Illuminate\Support\Facades\Lang;

class LinnaeusOptions
{
    public string $slug_key;
    public array $structure;
    public string $separator;
    public bool $create;
    public bool $update;
    public array $lists;
    public array $invalid_animals;
    public array $invalid_adjectives;
    public array $invalid_colors;

    public function __construct()
    {
        $this->slug_key = config('linnaeus.key', 'slug');
        $this->structure = config('linnaeus.structure', [
            'adjective',
            'adjective',
            'animal'
        ]);
        $this->separator = config('linnaeus.separator', '-');
        $this->create = config('linnaeus.create', true);
        $this->update = config('linnaeus.update', false);

        $this->invalid_animals = config('linnaeus.invalid_animals', []);
        $this->invalid_adjectives = config('linnaeus.invalid_adjectives', []);
        $this->invalid_colors = config('linnaeus.invalid_colors', []);

        $this->updateLists();
    }

    public static function create(): self
    {
        return new static();
    }

    public function withKey(string $key): self
    {
        $this->slug_key = $key;
        return $this;
    }

    public function withStructure(array $structure): self
    {
        $this->structure = $structure;
        $this->updateLists();
        return $this;
    }

    public function withSeparator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    public function generatesOnUpdate(): self
    {
        $this->update = true;
        return $this;
    }

    public function withInvalidAnimals(array $invalid): self
    {
        $this->invalid_animals = $invalid;
        return $this;
    }

    public function withInvalidAdjectives(array $invalid): self
    {
        $this->invalid_adjectives = $invalid;
        return $this;
    }

    public function withInvalidColors(array $invalid): self
    {
        $this->invalid_colors = $invalid;
        return $this;
    }

    private function updateLists()
    {
        $this->lists = [];
        foreach (array_unique($this->structure) as $slug_part) {
            $invalid_list = "invalid_{$slug_part}s";
            if (!empty($this->$invalid_list)) {
                $this->lists["{$slug_part}s"] = array_diff(Lang::get("linnaeus::{$slug_part}s"), $this->$invalid_list);
            } else {
                $this->lists["{$slug_part}s"] = Lang::get("linnaeus::{$slug_part}s");
            }
        }
    }
}