<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Category extends Model  implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'slug'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_images')
            ->singleFile();
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
}
