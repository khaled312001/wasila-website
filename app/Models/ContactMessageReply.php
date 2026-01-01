<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;

class ContactMessageReply extends Model
{
    protected $fillable = [
        'contact_message_id',
        'admin_id',
        'message',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'mime_type',
        'sender_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'file_size' => 'integer',
    ];

    protected $appends = ['file_url', 'is_image', 'is_video', 'is_audio', 'is_document'];

    public function contactMessage()
    {
        return $this->belongsTo(ContactMessage::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    public function getIsImageAttribute()
    {
        return $this->file_type === 'image';
    }

    public function getIsVideoAttribute()
    {
        return $this->file_type === 'video';
    }

    public function getIsAudioAttribute()
    {
        return $this->file_type === 'audio';
    }

    public function getIsDocumentAttribute()
    {
        return $this->file_type === 'document';
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }
}
