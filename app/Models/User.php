<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Rennokki\Plans\Traits\HasPlans;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , HasRoles, HasPlans;


    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'company_domain',
        'group_id',
        'password',
        'parent_user',
        'type',
        'prefered_color',
        'created_at',
        'updated_at'
    ];

    protected $appends = ['name'];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
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

    protected $guard_name = 'sanctum';
//    public function setCompanyDomainAttribute($value)
//    {
//        $this->attributes['company_domain'] = $value ?? strval( octdec(uniqid()) );
//    }
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
        return $this->belongsToMany(Kpi::class, 'kpi_user', 'user_id', 'kpi_id');
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

    public function company()
    {
        return $this->hasOne( Company::class , 'user_id');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class , 'user_id');
    }
}
