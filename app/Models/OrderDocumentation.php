<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderDocumentation extends Model
{
    use HasFactory;

    protected $table = 'order_documentation';

    protected $fillable = [
        'order_id',
        'title',
        'description',
        'video_path',
        'thumbnail_path',
        'duration',
        'file_size',
        'uploaded_by',
        'is_visible_to_customer',
        'viewed_at',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
    ];

    protected $casts = [
        'is_visible_to_customer' => 'boolean',
        'viewed_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getVideoUrlAttribute()
    {
        if (!$this->video_path) {
            return null;
        }

        // Clean the video path (remove 'storage/' prefix if exists)
        $cleanPath = str_replace('storage/', '', $this->video_path);
        $cleanPath = ltrim($cleanPath, '/');
        
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

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset('storage/' . $this->thumbnail_path) : null;
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return null;
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}

