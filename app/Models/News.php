<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string $text
 * @property bool $is_published
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class News extends Model
{
    use HasFactory, Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function save(array $option = [])
    {
        if ($this->exists && $this->isDirty('slug'))
        {
            $old_slug = $this->getOriginal('slug');
            $new_slug = $this->slug;

            $redirect = new Redirect();
            $redirect->old_slug = route('news_item', ['slug' => $old_slug], false);
            $redirect->new_slug = route('news_item', ['slug' => $new_slug], false);
            $redirect->save();
        }
        return parent::save($option);
    }
}
