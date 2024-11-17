<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type', 'name', 'path', 'mime_type'];

    // Automatically append the 'full_url' attribute when the model is serialized
    protected $appends = ['full_url'];

    public function documentable()
    {
        return $this->morphTo();
    }

    // Accessor for the 'full_url' attribute
    public function getFullUrlAttribute()
    {
        if ($this->path) {
            // Use the application's base URL
            $baseUrl = config('app.url');
            return $baseUrl . Storage::url($this->path);
        }

        return null;
    }

     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at'
    ];
}
