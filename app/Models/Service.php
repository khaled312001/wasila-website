<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'image',
        'category_ar',
        'category_en',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, OrderItem::class, 'service_id', 'id', 'id', 'order_id');
    }

    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function getCategoryAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->category_ar : $this->category_en;
    }

    /**
     * Get the image URL for the service
     * This method handles both symlink and non-symlink scenarios
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // Clean the image path (remove 'storage/' prefix if exists)
        $cleanImage = str_replace('storage/', '', $this->image);
        
        // First, try to use Storage URL (works with symlinks)
        if (Storage::disk('public')->exists($cleanImage)) {
            return Storage::disk('public')->url($cleanImage);
        }
        
        // Fallback: Check if file exists in public/storage (for non-symlink setups)
        $publicPath = public_path('storage/' . $cleanImage);
        if (file_exists($publicPath)) {
            return asset('storage/' . $cleanImage);
        }
        
        // Last resort: Try direct asset path
        return asset('storage/' . $cleanImage);
    }
}
