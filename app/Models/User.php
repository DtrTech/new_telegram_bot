<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Bouncer;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRolesAndAbilities,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'username',
        'role_id',
        'is_active',
        'contact_no',
        'referral_code',
        'wallet',
        'upline',
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

    public function getRoleNameAttribute()
    {
        $role = Bouncer::role()->where('id', $this->role_id)->first();
        if ($role) {
            return $role->name;
        } else {
            return null;
        }
    }

    public function getRoleTitleAttribute()
    {
        $role = Bouncer::role()->where('id', $this->role_id)->first();
        if ($role) {
            return $role->title;
        } else {
            return null;
        }
    }
}
