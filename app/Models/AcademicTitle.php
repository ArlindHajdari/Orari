<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 29 Mar 2017 13:21:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class AcademicTitle
 * 
 * @property int $id
 * @property string $academical_title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class AcademicTitle extends Eloquent
{
	protected $fillable = [
		'academical_title'
	];
}
