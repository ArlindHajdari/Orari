<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Role
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $permissions
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Role extends Eloquent
{
	protected $fillable = [
		'slug',
		'name',
		'permissions'
	];

	public function users()
	{
		return $this->belongsToMany(\App\Models\User::class, 'role_users')
					->withTimestamps();
	}

	public function getNameAttribute($value){
		return explode('_',$value)[1];
	}
}
