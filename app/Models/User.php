<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable 
// implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    const LIST_PERMISSION = 'list-admin-user';
    const VIEW_PERMISSION = 'view-admin-user';
    const ADD_PERMISSION = 'add-admin-user';
    const EDIT_PERMISSION = 'edit-admin-user';
    const DELETE_PERMISSION = 'delete-admin-user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
       'id'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    // public function getJWTCustomClaims()
    // {
    //     return [];
    // }

    // Relationships
    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }

    public function products(){
        return $this->hasMany('App\Models\Product','user_id');
    }

}
