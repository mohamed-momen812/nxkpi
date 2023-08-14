<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles;


    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'group_id',
        'password',
        'parent_user',
        'type',
        'created_at',
        'updated_at'
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


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function group(){
        return $this->belongsTo(Group::class , 'group_id');
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function entries(){
        return $this->hasMany(Entry::class);
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class , 'parent_user' , 'id');
    }

    public function empolyees()
    {
        return $this->hasMany(User::class , 'parent_user' , 'id');
    }

    public function rolesWithPermissions()
    {
        return $this->roles()->with('permissions');
    }

    public function addedPermissions()
    {
        return $this->permissions()->get();
    }
}
