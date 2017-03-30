<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 30 Mar 2017 17:53:37 +0000.
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
 * @property int $acedemic_title_id
 * @property string $photo
 * @property string $permissions
 * @property \Carbon\Carbon $last_login
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class User extends Eloquent
{
	protected $casts = [
		'log_id' => 'int',
		'cpa_id' => 'int',
		'acedemic_title_id' => 'int'
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
		'acedemic_title_id',
		'photo',
		'permissions',
		'last_login'
	];
}
