Setting.php
=============================================================
//shtoje kete tek uses: 
use Carbon\Carbon;

//Kete shtoje tek trupi i klases
public function getStartSummerSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getEndSummerSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getStartWinterSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}

	public function getEndWinterSemesterAttribute($value){
		return Carbon::parse($value)->toDateString();
	}
==============================================================
Role.php
==============================================================
public function getNameAttribute($value){
	return explode('_',$value)[1];
}
==============================================================
Cp.php
==============================================================
//nderro kete kod
public function ca()
	{
		return $this->hasMany(\App\Models\Ca::class, 'cps_id');
	}
==============================================================
Schedule.php
==============================================================
//Komentoje kete kod:
protected $dates = [
		'start',
		'end',
		'from',
		'to'
	];
==============================================================
GroupsLushSubjects.php
==============================================================
protected $table = "groups_lushsubjects";