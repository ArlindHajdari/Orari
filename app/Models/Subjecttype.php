<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Subjecttype
 * 
 * @property int $id
 * @property string $subjecttype
 *
 * @package App\Models
 */
class Subjecttype extends Eloquent
{
	public $timestamps = false;

	protected $fillable = [
		'subjecttype'
	];
}
