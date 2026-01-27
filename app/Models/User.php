<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,SoftDeletes, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
	
		public function getCreatedAtAttribute() {
		return date('d-m-Y H:i', strtotime($this->attributes['created_at']));
	}
	
	public function getUpdatedAtAttribute() {
		return date('d-m-Y H:i', strtotime($this->attributes['updated_at']));
	}
	
	public function getEmailVerifiedAtAttribute() {
		return $this->attributes['email_verified_at'] == null ? null : date('d-m-Y H:i', strtotime($this->attributes['email_verified_at']));
	}
	
	public function getPermissionArray() {
		return $this->getAllPermissions()->mapWithKeys(function ($pr) {
			return [$pr['name'] => true];
		});
	}
}
