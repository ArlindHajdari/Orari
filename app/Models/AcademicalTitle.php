<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 24 Mar 2017 13:42:17 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class AcademicalTitle
 * 
 * @property int $id
 * @property string $academical_title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class AcademicalTitle extends Eloquent
{
	protected $fillable = [
		'academical_title'
	];
}
