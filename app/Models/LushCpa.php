<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 17 Aug 2017 08:27:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class LushCpa
 * 
 * @property int $cpa_id
 * @property int $lush_id
 * 
 * @property \App\Models\Cpa $cpa
 * @property \App\Models\Lush $lush
 *
 * @package App\Models
 */
class LushCpa extends Eloquent
{
	protected $table = 'lush_cpa';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cpa_id' => 'int',
		'lush_id' => 'int'
	];

	protected $fillable = [
		'cpa_id',
		'lush_id'
	];

	public function cpa()
	{
		return $this->belongsTo(\App\Models\Cpa::class);
	}

	public function lush()
	{
		return $this->belongsTo(\App\Models\Lush::class);
	}
}
