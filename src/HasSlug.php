<?php

namespace MatteoGgl\Linnaeus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    protected LinnaeusOptions $linnaeus;

    public function getLinnaeusOptions(): LinnaeusOptions
    {
        return $this->linnaeus ?? LinnaeusOptions::create();
    }

    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->generateSlugOnCreate();
        });

        static::updating(function (Model $model) {
            $model->generateSlugOnUpdate();
        });
    }

    protected function generateSlugOnCreate()
    {
        if (!$this->getLinnaeusOptions()->create) {
            return;
        }

        $this->addSlug();
    }

    protected function generateSlugOnUpdate()
    {
        if (!$this->getLinnaeusOptions()->update) {
            return;
        }

        $this->addSlug();
    }

    protected function addSlug()
    {
        $slug_key = $this->getLinnaeusOptions()->slug_key;
        $this->$slug_key = $this->generateSlug();
    }

    protected function generateSlug(): string
    {
        return $this->getRandomSlug();
    }

    protected function getRandomSlug(): string
    {
        $slug = '';

        $lists = $this->getLinnaeusOptions()->getLists();

        foreach ($this->getLinnaeusOptions()->structure as $slug_part) {
            switch ($slug_part) {
                case 'animal':
                    $animal = array_rand($lists['animals']);
                    $slug .= trans("linnaeus::animals.$animal");
                    break;
                case 'adjective':
                    $adjective = array_rand($lists['adjectives']);
                    $slug .= trans("linnaeus::adjectives.$adjective");
                    break;
                case 'color':
                    $color = array_rand($lists['colors']);
                    $slug .= trans("linnaeus::adjectives.$color");
                    break;
            }
            $slug .= ' ';
        }

        if ($this->slugExists($slug)) {
            $slug = $this->getRandomSlug();
        }

        return Str::slug($slug, $this->getLinnaeusOptions()->separator);
    }

    protected function slugExists(string $slug): bool
    {
        $key = $this->getKey();
        if ($this->getIncrementing()) {
            $key ??= '0';
        }
        $query = static::where($this->getLinnaeusOptions()->slug_key, $slug)
            ->where($this->getKeyName(), '!=', $key)
            ->withoutGlobalScopes();

        if ($this->usesSoftDeletes()) {
            $query->withTrashed();
        }

        return $query->exists();
    }

    protected function usesSoftDeletes(): bool
    {
        return (bool)in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this));
    }

    public function getRouteKeyName()
    {
        return $this->getLinnaeusOptions()->slug_key;
    }
}
