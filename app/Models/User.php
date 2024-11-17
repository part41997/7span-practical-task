<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Hobby;
use App\Models\UserHobby;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'country_code',
        'mobile_number',
        'status',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

     /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_admin'];

    protected static function boot()
    {
        parent::boot();

        // Register a 'deleting' event to handle document deletion
        static::deleting(function ($user) {
            foreach ($user->documents as $document) {
                // Delete the file from storage
                if ($document->path && Storage::exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
                // Delete the document record from the database
                $document->delete();
            }
        });
    }

     /**
     * Create an accessor for the `is_admin` attribute.
     *
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->role->name === 'admin';
    }

     // Scope to filter by status
     public function scopeStatus($query, $status)
     {
         return $query->where('status', $status);
     }
 
     // Scope to filter by role
     public function scopeRole($query, $role)
     {
         return $query->where('role_id', $role);
     }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**User role */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**Profile photo */
    public function photo(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')->where('type', 'profile_photo');
    }

    /**All documents */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**All hobbies */
    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'user_hobbies')->select('hobbies.id', 'hobbies.name');
    }
}
