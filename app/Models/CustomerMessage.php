<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'message',
        'sender_type',
        'admin_id',
        'is_read',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function isImage()
    {
        return $this->file_type === 'image' || in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function isDocument()
    {
        return $this->file_type === 'document' || in_array($this->mime_type, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function isVideo()
    {
        return $this->file_type === 'video' || str_starts_with($this->mime_type ?? '', 'video/');
    }

    public function isAudio()
    {
        return $this->file_type === 'audio' || str_starts_with($this->mime_type ?? '', 'audio/');
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $size = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
}

