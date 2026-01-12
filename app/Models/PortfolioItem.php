<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PortfolioItem extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'type',
        'file_path',
        'thumbnail_path',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get the title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get the description based on current locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    /**
     * Get the file URL for the portfolio item
     * This method handles both symlink and non-symlink scenarios
     */
    public function getFileUrlAttribute()
    {
        $cleanPath = $this->normalized_file_path;
        if (!$cleanPath) {
            return null;
        }
        
        // First, try to use Storage URL (works with symlinks)
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }
        
        // Fallback: Check if file exists in public/storage (for non-symlink setups)
        $publicPath = public_path('storage/' . $cleanPath);
        if (file_exists($publicPath)) {
            return asset('storage/' . $cleanPath);
        }
        
        // Last resort: Try direct asset path
        return asset('storage/' . $cleanPath);
    }

    /**
     * Get the thumbnail URL for the portfolio item
     */
    public function getThumbnailUrlAttribute()
    {
        $cleanPath = $this->normalized_thumbnail_path;
        if (!$cleanPath) {
            return null;
        }
        
        // First, try to use Storage URL (works with symlinks)
        if (Storage::disk('public')->exists($cleanPath)) {
            return Storage::disk('public')->url($cleanPath);
        }
        
        // Fallback: Check if file exists in public/storage (for non-symlink setups)
        $publicPath = public_path('storage/' . $cleanPath);
        if (file_exists($publicPath)) {
            return asset('storage/' . $cleanPath);
        }
        
        // Last resort: Try direct asset path
        return asset('storage/' . $cleanPath);
    }

    /**
     * Normalized file path without storage/public prefixes
     */
    public function getNormalizedFilePathAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return $this->normalizePath($this->file_path);
    }

    /**
     * Normalized thumbnail path without storage/public prefixes
     */
    public function getNormalizedThumbnailPathAttribute(): ?string
    {
        if (!$this->thumbnail_path) {
            return null;
        }

        return $this->normalizePath($this->thumbnail_path);
    }

    /**
     * Remove known prefixes and leading slashes from a storage path
     */
    protected function normalizePath(string $path): string
    {
        $cleanPath = str_replace(['storage/', '/storage/', 'public/', '/public/'], '', $path);
        return ltrim($cleanPath, '/');
    }
}
