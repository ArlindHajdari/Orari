<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 05 Apr 2017 11:11:13 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class User
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $log_id
 * @property string $email
 * @property string $password
 * @property string $personal_number
 * @property int $cpa_id
 * @property int $academic_title_id
 * @property string $photo
 * @property string $permissions
 * @property \Carbon\Carbon $last_login
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\AcademicTitle $academic_title
 * @property \App\Models\Cpa $cpa
 * @property \Illuminate\Database\Eloquent\Collection $activations
 * @property \Illuminate\Database\Eloquent\Collection $availabilities
 * @property \App\Models\Ca $ca
 * @property \Illuminate\Database\Eloquent\Collection $cps
 * @property \Illuminate\Database\Eloquent\Collection $persistences
 * @property \Illuminate\Database\Eloquent\Collection $reminders
 * @property \Illuminate\Database\Eloquent\Collection $roles
 * @property \Illuminate\Database\Eloquent\Collection $throttles
 *
 * @package App\Models
 */
class User extends Eloquent
{
	protected $casts = [
		'log_id' => 'int',
		'cpa_id' => 'int',
		'academic_title_id' => 'int'
	];

	protected $dates = [
		'last_login'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'log_id',
		'email',
		'password',
		'personal_number',
		'cpa_id',
		'academic_title_id',
		'photo',
		'permissions',
		'last_login'
	];

	public function academic_title()
	{
		return $this->belongsTo(\App\Models\AcademicTitle::class);
	}

	public function cpa()
	{
		return $this->belongsTo(\App\Models\Cpa::class);
	}

	public function activations()
	{
		return $this->hasMany(\App\Models\Activation::class);
	}

	public function availabilities()
	{
		return $this->hasMany(\App\Models\Availability::class);
	}

	public function ca()
	{
		return $this->hasOne(\App\Models\Ca::class);
	}

	public function cps()
	{
		return $this->hasMany(\App\Models\Cp::class);
	}

	public function persistences()
	{
		return $this->hasMany(\App\Models\Persistence::class);
	}

	public function reminders()
	{
		return $this->hasMany(\App\Models\Reminder::class);
	}

	public function roles()
	{
		return $this->belongsToMany(\App\Models\Role::class, 'role_users')
					->withTimestamps();
	}

	public function throttles()
	{
		return $this->hasMany(\App\Models\Throttle::class);
	}
}
