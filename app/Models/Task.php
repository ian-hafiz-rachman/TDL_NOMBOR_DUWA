<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'priority',
        'status',
        'user_id',
        'image_path'
    ];
    
    // Tambahkan mutator untuk status jika diperlukan
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 