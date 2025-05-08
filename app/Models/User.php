<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\PasswordResetNotificaction;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, CanResetPassword;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
        'email',
        'password',
        'per_id',
        'rol_id',
        'estado',
        'is_deleted',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'per_id', 'per_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }


 

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotificaction($token));
    }
}
