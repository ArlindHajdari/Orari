<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Hall
 * 
 * @property int $id
 * @property string $hall
 * @property int $capacity
 * @property int $halltype_id
 *
 * @package App\Models
 */
class Hall extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'capacity' => 'int',
		'halltype_id' => 'int'
	];

	protected $fillable = [
		'hall',
		'capacity',
		'halltype_id'
	];
}