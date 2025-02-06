<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'user_id'
    ];

    protected $appends = ['image_url'];
    public function getImageUrlAttribute()
    {
        $mediaUrl = $this->getFirstMediaUrl('category_images');
        if (!$mediaUrl) {
            return null;
        }
        return url($mediaUrl);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('category_images')
            ->singleFile();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
