<?php
use App\Models\Setting;
use App\Models\Subject;
use App\Models\User;
use App\Models\StatusAcademicTitle;
use App\Models\Lush;
use App\Models\SubjectLush;
use App\Models\Group;
use App\Models\GroupsLushSubject;
use App\Models\Cp;
use App\Models\Ca;
use App\Models\Hall;
use App\Models\Schedule;
use App\Models\Availability;
use Carbon\Carbon;

function user(){
    return Sentinel::getUser();
}

function array_true($array){
    foreach($array as $value){
        if(!$value){
            return false;
        }
    }
    return true;
}

function intToString($number){
    $length = strlen((string)$number);
    $str_number = (string)$number;

    if($length == 1){
        return $str_number = '00'.$str_number;
    }elseif($length == 2){
        return $str_number = '0'.$str_number;
    }else{
        return $str_number;
    }
}

function getLushForSubject($subject_id){
    return Subject::select('lush.lush','lush.id')->
    join('subject_lush','subjects.id','subject_lush.subject_id')->
    join('lush','subject_lush.lush_id','lush.id')->
    where('subjects.id',$subject_id)->pluck('lush.lush','lush.id')->toArray();
}
//Merre stinen nga semestri(vere? dimer?)
function getSeasonFromSemester($semester){
    return ($semester%2 === 0) ? 1 : 0;
}

//Shiko nese mesimdhenesi i caktuar eshte asistent per lenden e caktuar
function isAssistantForSubject($user_id,$subject_id){
    if(CP::whereSubjectId($subject_id)->whereUserId($user_id)->exists()){
        return false;
    }elseif(CA::join('cps','ca.cps_id','cps.id')->where('ca.user_id',$user_id)->where('cps.subject_id',$subject_id)->exists()){
        return true;
    }else{
        return -1;
    }
}

function getGroupsFromLushAndSubject($subject_id,$lush_id){
    $groups = GroupsLushSubject::select('groups_lushsubjects.group_id')->
    join('subject_lush','groups_lushsubjects.subject_lush_id','subject_lush.id')->
    where('subject_lush.subject_id',$subject_id)->where('subject_lush.lush_id',$lush_id)->pluck('groups_lushsubjects.group_id')->toArray();

    return ($groups != null) ? $groups : Group::select('id')->whereId('1')->pluck('id')->toArray();
}

//Merr group_id=>group varesisht prej oreve te ushtrimeve per cps e caktuar
function getGroups($user_id,$subject_id,$lush_id){
    $lecture_hours = 0;

    if(LUSH::find($lush_id)->lush == 'Ligjëratë') {
        $temp = CP::select('lecture_hours')->where('user_id', $user_id)->where('subject_id', $subject_id)->first()['lecture_hours'];
        $lecture_hours = floor($temp/2);

        $groups = Group::where('id', '<=', $lecture_hours)->pluck('id')->toArray();
    }elseif (LUSH::find($lush_id)->lush == 'Ushtrime') {
        $temp = CP::select('cps.exercise_hours')->join('ca', 'cps.id', 'ca.cps_id')->where('ca.user_id', $user_id)->where('cps.subject_id', $subject_id)->first()['exercise_hours'];
        if(getNumberOfAssistants(getCps($user_id,$subject_id)) == 1){
            $lecture_hours = floor($temp/2);

            $groups = Group::where('id', '<=', $lecture_hours)->pluck('id')->toArray();
        }else{
            $existingGroups = Schedule::select('group_id')->whereUserId($user_id)->whereSubjectId($subject_id)->pluck('group_id');
            $lecture_hours = floor($temp/getNumberOfAssistants(getCps($user_id,$subject_id)));

            $groups = Group::where('id', '<=', $lecture_hours)->whereNotIn('id',$existingGroups)->pluck('id')->toArray();
        }
    }

    return $groups;
}

function timeOverLap($time_toBeChosen, $availability){
    // $time_toBeChosen = [
    //     '2017-04-17'=>[['start'=>'08:00:00','end'=>'20:00:00']],
    //     '2017-04-18'=>[['start'=>'08:00:00','end'=>'20:00:00']],
    //     '2017-04-19'=>[['start'=>'08:00:00','end'=>'20:00:00']],
    //     '2017-04-20'=>[['start'=>'08:00:00','end'=>'20:00:00']],
    //     '2017-04-21'=>[['start'=>'08:00:00','end'=>'20:00:00']],
    //     '2017-04-22'=>[['start'=>'08:00:00','end'=>'20:00:00']]
    // ];

    //$availability = $availability['allowed']

    $firstKey = array_values(array_keys($time_toBeChosen))[array_rand(array_keys($time_toBeChosen))];
    $secondKey = array_values(array_keys($time_toBeChosen[$firstKey]))[array_rand(array_keys($time_toBeChosen[$firstKey]))];
    $thirdKey = array_values(array_keys($time_toBeChosen[$firstKey][$secondKey]))[array_rand(array_keys($time_toBeChosen[$firstKey][$secondKey]))];

    //return timedomain that overlap
    if(count($availability) > 0){
        for($i = 0; $i < count($availability); $i++){
            $overlapTimes = array();
            //Nese ka me shume se nje disponueshmeri per diten e caktuar
            foreach(countSameString($availability,'DATE(start)',$availability[$i]['DATE(start)'])['indexes'] as $index){
                $start_not_allowed = $availability[$index]['DATE(start)'].' '.$availability[$i]['TIME(start)'];
                $end_not_allowed = $availability[$index]['DATE(start)'].' '.$availability[$i]['TIME(end)'];

                //Nese ka nje ose me shume start edhe end tek time_toBeChosen
                foreach($time_toBeChosen[$availability[$index]['DATE(start)']] as $key=>$value){
                    foreach($value as $endNode){
                        $start_time_toBeChosen = $endNode['start'];
                        $end_time_toBeChosen = $endNode['end'];

                        if(Carbon::parse($start_time_toBeChosen)->between(Carbon::parse($start_not_allowed),Carbon::parse($end_not_allowed)) && Carbon::parse($end_time_toBeChosen)->between(Carbon::parse($start_not_allowed),Carbon::parse($end_not_allowed))){
                            $overlapTimes[] = ['start'=>$start_time_toBeChosen, 'end'=>$end_time_toBeChosen];
                        }
                    }
                }
            }
            return $overlapTimes[array_values(array_keys($overlapTimes))[array_rand(array_keys($overlapTimes))]];
        }
        //Kthe nje vlere random
        return $time_toBeChosen[$firstKey][$secondKey][$thirdKey];
    }else{
        return $time_toBeChosen[$firstKey][$secondKey][$thirdKey];
    }
}

//Merr numrin e asisteneve per cps e caktuar
function getNumberOfAssistants($cps_id){
    return CA::whereCpsId($cps_id)->count();
}

//Merre lush_id nga mesimdhenesi dhe lenda(asistent/profesor => ushtrime/ligjerate)
function getLush($user_id,$subject_id){
    $isProfessorForGivenSubject = CP::whereUserId($user_id)->whereSubjectId($subject_id)->exists();

    if($isProfessorForGivenSubject){
        return Lush::select('id')->whereLush('Ligjëratë')->first()->id;
    }else{
        return Lush::select('id')->whereLush('Ushtrime')->first()->id;
    }
}

function getLushFromSubject($subject_id){
    return SubjectLush::select('lush.id','lush.lush')->join('lush','subject_lush.lush_id','lush.id')->whereSubjectId($subject_id)->pluck('lush.lush','lush.id')->toArray();
}

function getTeacherFromId($id){
    return User::selectRaw('concat(academic_titles.academic_title," ",users.first_name," ",users.last_name) as full_name')->
        join('academic_titles','users.academic_title_id','academic_titles.id')->where('users.id',$id)->first()->full_name;
}

function getTeachers($semester,$isProfessor = null){
    if($isProfessor == null){
        $teachers = CP::select('cps.user_id')->join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        where('subjects.semester',$semester)->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1]);

        $data = CA::select('ca.user_id')->join('cps','ca.cps_id','cps.id')->
        join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        where('subjects.semester',$semester)->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->union($teachers)->distinct()->orderBy('user_id')->pluck('ca.user_id')->toArray();


        return ($data != null) ? $data : [-1];
    }elseif($isProfessor==1){
        $teachers = CP::select('cps.user_id')->join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        where('subjects.semester',$semester)->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->distinct()->pluck('cps.user_id')->toArray();

        return ($teachers != null) ? $teachers : [-1];
    }elseif($isProfessor==2){
        $assistants = CA::select('ca.user_id')->join('cps','ca.cps_id','cps.id')->
        join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        where('subjects.semester',$semester)->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->distinct()->pluck('ca.user_id')->toArray();

        return ($assistants != null) ? $assistants : [-1];
    }
}

function getAssistants($cps_id){
    $assistants = CA::select('ca.user_id')->join('cps','ca.cps_id','cps.id')->
    where('ca.cps_id',$cps_id)->pluck('ca.user_id')->toArray();

    return $assistants;
}

function getSubjects(){
    if(func_num_args() == 2){
        $data = func_get_args();
        $subjects = CP::select('cps.subject_id')->join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        join('ca','cps.id','ca.cps_id')->
        where('subjects.semester',$data[0])->
        where(function($query) use($data){
            $query->orWhere('cps.user_id',$data[1]);
            $query->orWhere('ca.user_id',$data[1]);
        })->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->distinct()->pluck('cps.subject_id')->toArray();

        return ($subjects != null) ? $subjects : [-1];
    }elseif(func_num_args() == 1){
        $subjects = CP::select('cps.subject_id')->join('subjects','cps.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        // where('subjects.semester', func_get_args()[0])->
        where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->distinct()->pluck('cps.subject_id')->toArray();

        return ($subjects != null) ? $subjects : [-1];
    }
}

//Merri cps_id te $semester-it
function getCPSFromSemester($semester){
    return CP::with(['subject' => function($query) use ($semester){
        $query->whereSemester($semester);
    }])->whereHas('subject.department.faculty',function($query){
        $query->whereFaculty(explode('_',user()->roles()->first()->slug)[1]);
    })->get();
}

//Merri sallat prej fakultetit
function getHallsFromFaculty($faculty){
    return Hall::select('halls.id','halltypes.hallType')->
    join('halltypes','halls.halltype_id','halltypes.id')->
    join('faculties','halls.faculty_id','faculties.id')->
    where('faculties.faculty',$faculty)->
    pluck('hallType','id')->toArray();
}

//Merr settings te adminit
function settings(){
    return Setting::whereHas('user.roles',function($query){
            $query->whereSlug('admin');
        })->get()->toArray()[0];
}

//Merr semestrin nga lenda
function getSemesterFromSubject($subject_id){
    return Subject::find($subject_id)->semester;
}

//Merre lenden nga cps_id
function getSubjectFromCPS($cps_id){
    return Subject::whereHas('cps',function($query) use($cps_id){
        $query->whereId($cps_id);
    })->get();
}

//Merr normal_hours + extra_hours te profesorit te caktuar(oret javore)
function getStatusHours($user_id){
    $user = User::find($user_id);

    return  (StatusAcademicTitle::select(DB::raw('(normal_hours + extra_hours) as hours'))->whereAcademicTitleId($user->academic_title_id)->whereStatusId($user->status_id)->exists()) ? StatusAcademicTitle::select(DB::raw('(normal_hours + extra_hours) as hours'))->whereAcademicTitleId($user->academic_title_id)->whereStatusId($user->status_id)->get()[0]["hours"] : -1;
}

//Merre oret e profesorit per lenden e caktuar
function getTeacherHours($user_id,$subject_id,$lush_id=null){
    if(null === $lush_id){
        return (CP::select('lecture_hours')->whereUserId($user_id)->whereSubjectId($subject_id)->exists()) ? CP::select('lecture_hours')->whereUserId($user_id)->whereSubjectId($subject_id)->first()['lecture_hours'] : CP::select('exercise_hours')->whereHas('ca',function($query) use($user_id){
            $query->whereUserId($user_id);
        })->whereSubjectId($subject_id)->first()['exercise_hours'];
    }else{
        if(Lush::find($lush_id)->lush == "Ligjëratë"){
            return CP::select('lecture_hours')->whereUserId($user_id)->whereSubjectId($subject_id)->first()->lecture_hours;
        }else{
            return CP::select('exercise_hours')->whereHas('ca',function($query) use($user_id){
                $query->whereUserId($user_id);
            })->whereSubjectId($subject_id)->first()->exercise_hours;
        }
    }
}

//A eshte profesori i caktuar i lire?
function isTeacherAvailable($start,$end,$user_id,$semester){
    return !(Schedule::select('user_id')->where(function ($query) use ($start,$end) {
        $query->where(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($start)->addMinute()->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($end)->subMinute()->toTimeString());
            $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($start)->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($start)->addMinute()->toTimeString());
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($start)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($end)->subMinute()->toTimeString());
        });
    })->where(function ($query) use ($user_id,$start,$semester) {
        $query->where('user_id', $user_id);
        $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($start)->format('l'));
        $query->where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester']);
    })->exists()) ? 1 : 0;
}

//A eshte grupi i caktuar i lire?
function isGroupAvailable($start,$end,$semester,$group_id=null){
    return !(Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($start,$end) {
        $query->where(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(schedule.end)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(schedule.end)'), '>', Carbon::parse($start)->addMinute()->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(schedule.start)'), '<', Carbon::parse($end)->subMinute()->toTimeString());
            $query->where(DB::raw('TIME(schedule.start)'), '>', Carbon::parse($start)->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(schedule.start)'), '<', Carbon::parse($start)->addMinute()->toTimeString());
            $query->where(DB::raw('TIME(schedule.start)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(schedule.end)'), '>', Carbon::parse($start)->toTimeString());
            $query->where(DB::raw('TIME(schedule.end)'), '>', Carbon::parse($end)->subMinute()->toTimeString());
        });
    })->where(function ($query) use ($semester,$start,$group_id) {
        if($group_id != null){
            $query->where(DB::raw('DAYNAME(schedule.start)'), Carbon::parse($start)->format('l'));
            $query->where('subjects.semester', $semester);
            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
            $query->where('schedule.to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester']);
            $query->where(function($query) use($group_id){
                $query->where('schedule.group_id', $group_id);
                $query->orWhere('schedule.group_id', null);
            });
        }else{
            $query->where(DB::raw('DAYNAME(schedule.start)'), Carbon::parse($start)->format('l'));
            $query->where('subjects.semester', $semester);
            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
            $query->where('schedule.to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester']);
        }
    })->exists()) ? 1 : 0;
}

function getNumberOfSchedules($semester){
    return CP::whereHas('subject',function ($query) use($semester){
        $query->where('semester',$semester);
    })->whereHas('subject.department.faculty',function(){
        $query->orWhere('faculty',explode('_',user()->roles()->first()->slug)[1]);
    })->count();
}

//A eshte salla e caktuar e lire?
function isHallAvailable($start,$end,$hall_id,$semester){
    return !(Schedule::select('hall_id')->where(function ($query) use ($start,$end) {
        $query->where(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($start)->addMinute()->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($end)->subMinute()->toTimeString());
            $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($start)->toTimeString());
        });
        $query->orWhere(function ($query) use ($start,$end) {
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($start)->addMinute()->toTimeString());
            $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($end)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($start)->toTimeString());
            $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($end)->subMinute()->toTimeString());
        });
    })->where(function ($query) use ($hall_id,$start,$semester) {
        $query->where('hall_id', $hall_id);
        $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($start)->format('l'));
        $query->where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester']);
    })->exists()) ? 1 : 0;
}

//zbrit kohen $time nga $fromTime
function subtractTime($fromTime, $time){
    //shembull: $time = ['start'=>'2017-06-06 08:00:00','end'=>'2017-06-06 09:30:00']
    $data = array();
    //Nese fromTime eshte 0 dhe nuk ka kohe atehere kthe 0
    if(Carbon::parse($fromTime['end'])->diffInMinutes(Carbon::parse($fromTime['start'])) == 0 && (Carbon::parse($fromTime['start'])->toTimeString() == '00:00:00')){
        return array(['start'=>Carbon::parse($fromTime['start'])->toDateString().' 00:00:00','end'=>Carbon::parse($fromTime['end'])->toDateString().' 00:00:00']);
    }
    //nese $fromTime eshte me e madhe ose e barabarte se $time
    if(Carbon::parse($fromTime['end'])->diffInMinutes(Carbon::parse($fromTime['start'])) >= Carbon::parse($time['end'])->diffInMinutes(Carbon::parse($time['start']))){
        //Nese $fromTime eshte e barabarte me $time
        if(!(Carbon::parse($fromTime['end'])->diffInMinutes(Carbon::parse($fromTime['start'])) == Carbon::parse($time['end'])->diffInMinutes(Carbon::parse($time['start'])))){
            //nese nuk jane te njejte dy vargjet
            if(Carbon::parse($fromTime['end'])->toTimeString() == Carbon::parse($time['end'])->toTimeString()){
                //nese fundi eshte i njejte
                $data[0]['start'] = Carbon::parse($fromTime['start'])->toDateTimeString();
                $data[0]['end'] = Carbon::parse($time['start'])->toDateTimeString();
            }elseif(Carbon::parse($fromTime['start'])->toTimeString() == Carbon::parse($time['start'])->toTimeString()){
                //nese fillimi eshte i njejte
                $data[0]['start'] = Carbon::parse($time['end'])->toDateTimeString();
                $data[0]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
            }else{
                //nese fundi dhe fillimi i dy vargjeve nuk jane te njejte
                if((Carbon::parse($fromTime['end'])->toTimeString() < Carbon::parse($time['end'])->toTimeString()) && Carbon::parse($fromTime['end'])->between(Carbon::parse($time['start']),Carbon::parse($time['end']))){
                    $data[0]['start'] = Carbon::parse($fromTime['start'])->ToDateTimeString();
                    $data[0]['end'] = Carbon::parse($time['start'])->toDateTimeString();
                }elseif((Carbon::parse($fromTime['start'])->toTimeString() > Carbon::parse($time['start'])->toTimeString()) && Carbon::parse($fromTime['start'])->between(Carbon::parse($time['start']),Carbon::parse($time['end']))){
                    $data[0]['start'] = Carbon::parse($time['end'])->ToDateTimeString();
                    $data[0]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
                }elseif((Carbon::parse($fromTime['start'])->toTimeString() < Carbon::parse($time['start'])->toTimeString()) && Carbon::parse($fromTime['end'])->toTimeString() > Carbon::parse($time['end'])->toTimeString()){
                    $data[0]['start'] = Carbon::parse($fromTime['start'])->toDateTimeString();
                    $data[0]['end'] = Carbon::parse($time['start'])->toDateTimeString();
                    $data[1]['start'] = Carbon::parse($time['end'])->toDateTimeString();
                    $data[1]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
                }else{
                    $data[0]['start'] = Carbon::parse($fromTime['start'])->ToDateTimeString();
                    $data[0]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
                }
            }
        }else{
            return array(['start'=>Carbon::parse($fromTime['start'])->toDateString().' 00:00:00','end'=>Carbon::parse($fromTime['end'])->toDateString().' 00:00:00']);
        }
    }else{
        if(Carbon::parse($fromTime['end'])->toTimeString() < Carbon::parse($time['end'])->toTimeString() && Carbon::parse($fromTime['end'])->between(Carbon::parse($time['start']),Carbon::parse($time['end']))){
            $data[0]['start'] = Carbon::parse($fromTime['start'])->ToDateTimeString();
            $data[0]['end'] = Carbon::parse($time['start'])->toDateTimeString();
        }elseif((Carbon::parse($fromTime['start'])->toTimeString() > Carbon::parse($time['start'])->toTimeString()) && Carbon::parse($fromTime['start'])->between(Carbon::parse($time['start']),Carbon::parse($time['end']))){
            $data[0]['start'] = Carbon::parse($time['end'])->ToDateTimeString();
            $data[0]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
        }else{
            if(Carbon::parse($fromTime['start'])->between(Carbon::parse($time['start']),Carbon::parse($time['end'])) && Carbon::parse($fromTime['end'])->between(Carbon::parse($time['start']),Carbon::parse($time['end']))){
                return array(['start'=>Carbon::parse($fromTime['start'])->toDateString().' 00:00:00','end'=>Carbon::parse($fromTime['end'])->toDateString().' 00:00:00']);
            }else{
                $data[0]['start'] = Carbon::parse($fromTime['start'])->ToDateTimeString();
                $data[0]['end'] = Carbon::parse($fromTime['end'])->toDateTimeString();
            }
        }
    }
    return $data;
}

//Njeh sa elemente me $key jon tnjejta edhe kthen indexat me numrin e pergjithshem te tyre
function countSameString($array,$key,$string){
    $count=0;
    $indexes = array();
    for($i = 0; $i < count($array); $i++){
        if($array[$i][$key] == $string){
            $count = $count + 1;
            $indexes[] = $i;
        }
    }
    return ['count'=>$count,'indexes'=>$indexes];
}

function removeTimeFromTimeDomain($busyTime,&$timeToBeChosen){
    if(count($busyTime) > 0){
        for($i = 0; $i < count($busyTime); $i++){
            //Nese ka me shume se nje disponueshmeri per diten e caktuar
            foreach(countSameString($busyTime,'DATE(start)',$busyTime[$i]['DATE(start)'])['indexes'] as $index){
                $start_not_allowed = $busyTime[$index]['DATE(start)'].' '.$busyTime[$i]['TIME(start)'];
                $end_not_allowed = $busyTime[$index]['DATE(start)'].' '.$busyTime[$i]['TIME(end)'];

                //Nese ka me shume se nje start edhe end tek time_toBeChosen
                foreach($timeToBeChosen[$busyTime[$index]['DATE(start)']] as $key=>$value){
                    $start_time_toBeChosen = $busyTime[$index]['DATE(start)'].' '.$value['start'];
                    $end_time_toBeChosen = $busyTime[$index]['DATE(start)'].' '.$value['end'];
                    //Dergo pointerin tek elementi i fundit
                    end($timeToBeChosen[$busyTime[$index]['DATE(start)']]);
                    $key = key($timeToBeChosen[$busyTime[$index]['DATE(start)']]);

                    foreach(subtractTime(['start'=>$start_time_toBeChosen,'end'=>$end_time_toBeChosen],['start'=>$start_not_allowed,'end'=>$end_not_allowed]) as $value){
                        $timeToBeChosen[$busyTime[$index]['DATE(start)']][] = ['start'=>explode(' ',$value['start'])[1],'end'=>explode(' ',$value['end'])[1]];
                    }
                    //Fshije elementin e vjeter prej te cilit jane futur te dhenat ne foreach-in siper ^
                    unset($timeToBeChosen[$busyTime[$index]['DATE(start)']][$key]);
                }
            }
        }
    }
}

//ndaje kohen ne dy pjese per oret e caktuara
function splitTimeFromHours($time,$minutes,&$data,$count,$definedHour=45){
    //shembull: $time = ['start'=>'2017-06-06 08:00:00','end'=>'2017-06-06 09:30:00']
    //Kontrollo nese kohezgjatje e $time eshte me e madhe se $hours
    if(Carbon::parse($time['end'])->diffInMinutes(Carbon::parse($time['start'])) >= intval($minutes)){
        //Rasti kur kohezgjatja e $time eshte me e madhe se $hours
        $data[$count]['start'] = Carbon::parse($time['start'])->toDateTimeString();
        $data[$count]['end'] = Carbon::parse($time['start'])->addMinutes($minutes)->toDateTimeString();

        if(Carbon::parse($time['end'])->diffInMinutes(Carbon::parse($time['start'])->addMinutes($minutes)) >= intval($minutes)){
            splitTimeFromHours(['start'=>Carbon::parse($time['start'])->addMinutes($minutes)->toDateTimeString(),'end'=>Carbon::parse($time['end'])->toDateTimeString()],$minutes,$data,$count+1);
        }
    }else{
        if(Carbon::parse($time['end'])->diffInMinutes(Carbon::parse($time['start'])) < $definedHour){
            $data[$count]['start'] = null;
            $data[$count]['end'] = null;
        }else{
            $data[$count]['start'] = Carbon::parse($time['start'])->toDateTimeString();
            $data[$count]['end'] = Carbon::parse($time['end'])->toDateTimeString();
        }
    }
    return $data;
}

//Merre minutat e pergjithshem kur salla eshte e nxene
function getHallMinutes($hall_id,$semester){
    return Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as domain'))->whereHallId($hall_id)->whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->first()->domain/60;
}

//Shiko nese salla e caktuar ka vend per ligjerate/ushtrime
function hasHallSpace($hall_id,$user_id,$subject_id,$lush_id){
    $max_minutes = 2880; //Minutat per gjithe javen prej ores 8 deri ne ora 20

    $lecture_minutes = getSubjectHours($user_id,$subject_id,$lush_id)[0];

    $currentHallMinutes = getHallMinutes($hall_id,getSemesterFromSubject($subject_id));

    return ($max_minutes - $currentHallMinutes) > $lecture_minutes;
}

function getGroupsL($user_id,$subject_id){
    $lecture_hours = 0;

    $lush_id = getLush($user_id,$subject_id);
    $groups = null;
    if(LUSH::find($lush_id)->lush == 'Ligjëratë') {
        $temp = CP::select('lecture_hours')->where('user_id', $user_id)->where('subject_id', $subject_id)->first()['lecture_hours'];
        $lecture_hours = floor($temp/2);

        $groups = Group::where('id', '<=', $lecture_hours)->pluck('group', 'id')->toArray();
    }elseif (LUSH::find($lush_id)->lush == 'Ushtrime') {
        $temp = CP::select('cps.exercise_hours')->join('ca', 'cps.id', 'ca.cps_id')->where('ca.user_id', $user_id)->where('cps.subject_id', $subject_id)->first()['exercise_hours'];
        if(getNumberOfAssistants(getCps($user_id,$subject_id)) == 1){
            $lecture_hours = floor($temp/2);

            $groups = Group::where('id', '<=', $lecture_hours)->pluck('group', 'id')->toArray();
        }else{
            $existingGroups = Schedule::select('group_id')->whereUserId($user_id)->whereSubjectId($subject_id)->pluck('group_id');
            $lecture_hours = floor($temp/getNumberOfAssistants(getCps($user_id,$subject_id)));

            $groups = Group::where('id', '<=', $lecture_hours)->whereNotIn('id',$existingGroups)->pluck('group', 'id')->toArray();
        }
    }

    return $groups;
}

function getSubjectMinutes($user_id,$subject_id,$lush_id){
    //Kshyre ndreqi per ushtrime nese ka shume grupe caktoje kohen sakte per secilin grup
    $cps_id = getCPS($user_id,$subject_id);
    $data = CP::with('ca')->whereId($cps_id)->get()->toArray()[0];
    $semester = getSemesterFromSubject($data['subject_id']);

    if(LUSH::find($lush_id)->lush == "Ligjëratë"){
        $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
            whereUserId($user_id)->whereSubjectId($subject_id)->
            whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/60;

        return [$data['lecture_hours']*45-$lecture_hours];
    }else{
        $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(schedule.end,schedule.start))) as diff'))->
            where('user_id',$user_id)->
            where('subject_id',$subject_id)->
            where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/60;
        return [$data['exercise_hours']*45-$exercise_hours];
    }

}

///kthen array me kohen qe mundet me hi per cps e caktuar
function generateTimeDomain($user_id,$subject_id,$group_id=null,&$hall_id,&$lush_id,$min_minutes=45,$break_minutes=15,$definedHour=45){

    if(hasSubjectFinished($user_id,$subject_id)){
        return true;
    }
    //Merre semestrin prej lendes
    $semester = getSemesterFromSubject($subject_id);

    //Merre sallat per fakultetin e dekanit qe osht logu
    $halls_ids = getHallsFromFaculty(explode('_',user()->roles()->first()->slug)[1]);

    $hall_id = -1;
    $lush_id = getLush($user_id,$subject_id);
    //Merr kabinet nese eshte fjala per ushtrime, nese nuk ka kabinet merre salle te ligjeratave
    if(isAssistantForSubject($user_id,$subject_id) && in_array('Kabinet',$halls_ids)){
        foreach($halls_ids as $key=>$hall){
            if($hall == 'Kabinet'){
                if(hasHallSpace($key,$user_id,$subject_id,$lush_id)){
                    $hall_id = $key;
                    break;
                }
            }
        }
    }


    if($hall_id == -1){
        foreach($halls_ids as $key=>$hall){
            if($hall == 'Ligjëratë'){
                if(hasHallSpace($key,$user_id,$subject_id,$lush_id)){
                    $hall_id = $key;
                    break;
                }
            }
        }
    }

    //Disponueshmeria e mesimdhenesit ($availability['allowed'] edhe $availability['not_allowed'])
    $availability = getTeacherAvailability($user_id);

    //Oret e lejuara per jave prej status<->cpa
    $extraAndNormal_hours = getStatusHours($user_id);

    //Oret e caktuara per ligjerate ose ushtrime
    $lectureOrExercise_hours = getTeacherHours($user_id,$subject_id,$lush_id);

    //Oret qe kan mbet per me i fut ne orar per lenden e caktuar
    $subjectMinutesLeft = getSubjectMinutes($user_id,$subject_id,$lush_id)[0];

    //Oret maksimale per dite
    $maxDay_hours = (LUSH::find($lush_id)->lush == "Ligjëratë") ? settings()['max_hour_day_lecture'] : settings()['max_hour_day_exercise'];

    //Koha kur mesimdhenesi eshte i nxene
    $teacherBusyTime = Schedule::select(DB::raw('DATE(start),TIME(start),TIME(end)'))->
    whereUserId($user_id)->
    whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->
    orderBy('start','ASC')->get()->toArray();

    //Koha kur salla eshte e nxene
    $hallBusyTime = Schedule::select(DB::raw('DATE(start),TIME(start),TIME(end)'))->
    whereHallId($hall_id)->whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->
    orderBy('start','ASC')->get()->toArray();

    //Koha kur grupi eshte i nxene
    $groupBusyTime = Schedule::select(DB::raw('DATE(start),TIME(start),TIME(end)'))
        ->where(function($query) use($group_id){
            if($group_id != null){
                $query->whereGroupId($group_id);
                $query->OrWhere('group_id',null);
            }
        })
        ->whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])
        ->whereHas('subject.department.faculty', function($query) {
            $query->whereFaculty(explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
        })
        ->whereHas('subject', function($query) use($semester){
            $query->whereSemester($semester);
        })->get()->toArray();

    //Te gjitha mundesit per me u zgjedh orari
    $time_toBeChosen = [
        '2017-04-17'=>[['start'=>'08:00:00','end'=>'20:00:00']],
        '2017-04-18'=>[['start'=>'08:00:00','end'=>'20:00:00']],
        '2017-04-19'=>[['start'=>'08:00:00','end'=>'20:00:00']],
        '2017-04-20'=>[['start'=>'08:00:00','end'=>'20:00:00']],
        '2017-04-21'=>[['start'=>'08:00:00','end'=>'20:00:00']],
        '2017-04-22'=>[['start'=>'08:00:00','end'=>'20:00:00']]
    ];

    //Nese mesimdhenesi ka ne disponueshmeri kohe qe nuk mundet te vije
    removeTimeFromTimeDomain($availability['not_allowed'],$time_toBeChosen);

    //Nese mesimdhenesi eshte i nxene(ligjerate/ushtrime) largo kohen kur ai eshte i nxene
    removeTimeFromTimeDomain($teacherBusyTime,$time_toBeChosen);

    //Nese salla eshte e nxene, largo kohen kur salla eshte e nxene
    removeTimeFromTimeDomain($hallBusyTime,$time_toBeChosen);

    //Nese grupi eshte i nxene, largo kohen kur grupi eshte i nxene
    removeTimeFromTimeDomain($groupBusyTime,$time_toBeChosen);

    //Ndaj kohen sipas rregullave
    $splitHours = (min($subjectMinutesLeft/45,$maxDay_hours) > 3) ? 2 : min($subjectMinutesLeft/45,$maxDay_hours);

    $temp = array();

    //Shiko per vleren me te madhe te mundshme per ndarje te kohes (oret e mbetura per lenden, oret e mbetura per me e arrit limit per dite)
    foreach($time_toBeChosen as $key=>$day){
        foreach($day as $domain){
            $diqka = array();
            $temp[$key][] = splitTimeFromHours(['start'=>$key.' '.$domain['start'],'end'=>$key.' '.$domain['end']],$splitHours*45,$diqka,0);
        }
    }

    if($temp == null){
        return false;
    }

    //Fshije elementet qe jane null
    foreach($temp as $key=>$day){
        foreach($day as $key1=>$domain){
            foreach($domain as $key2=>$time){
                if($time['start'] == null && $time['end'] == null){
                    unset($temp[$key][$key1][$key2]);
                }
            }
        }
    }

    //Fshi elementet qe jane te zbrazeta
    foreach($temp as $key=>$day){
        foreach($day as $key1=>$domain){
            if(empty($domain)){
                unset($temp[$key][$key1]);
            }
        }
    }

    foreach($temp as $key1=>$domain){
        if(empty($domain)){
            unset($temp[$key1]);
        }
    }

//    $firstKey = array_values(array_keys($temp))[array_rand(array_keys($temp))];
//    $secondKey = array_values(array_keys($temp[$firstKey]))[array_rand(array_keys($temp[$firstKey]))];
//    $thirdKey = array_values(array_keys($temp[$firstKey][$secondKey]))[array_rand(array_keys($temp[$firstKey][$secondKey]))];
//    return $temp[$firstKey][$secondKey][$thirdKey];
     return timeOverLap($temp,$availability['allowed']);
}

//ruan orarin per te dhenat e caktuara
function storeSchedule($start,$end,$user_id,$subject_id,$group_id=null,$hall_id,$lush_id,$semester,$to_be_deleted = 0){
    $schedule = new Schedule;
    $schedule->start = $start;
    $schedule->end = $end;
    $schedule->user_id = $user_id;
    $schedule->hall_id = $hall_id;
    $schedule->subject_id = $subject_id;
    ($group_id == null || empty($group_id)) ?: $schedule->group_id = $group_id;
    $schedule->lush_id = $lush_id;
    $schedule->from = (!getSeasonFromSemester($semester)) ? settings()['start_winter_semester'] : settings()['start_summer_semester'];
    $schedule->to = (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'];
    ($to_be_deleted != 0) ?: $schedule->to_be_deleted = $to_be_deleted;

    return $schedule->save();
}

//merre disponueshmerine per profin te degezuar ne allowed dhe not_allowed
function getTeacherAvailability($user_id){
    $allowed_availability = Availability::select(DB::raw('DATE(start),TIME(start),TIME(end)'))->whereUserId($user_id)->whereAllowed(1)->orderBy('start','ASC')->get()->toArray();
    $notAllowed_availability = Availability::select(DB::raw('DATE(start),TIME(start),TIME(end)'))->whereUserId($user_id)->whereAllowed(0)->orderBy('start','ASC')->get()->toArray();

    $data['allowed'] = $allowed_availability;
    $data['not_allowed'] = $notAllowed_availability;

    return $data;
}

function getCPS($user_id,$subject_id){
    if(CP::whereSubjectId($subject_id)->whereUserId($user_id)->exists()){
        return CP::select('id')->whereSubjectId($subject_id)->whereUserId($user_id)->first()->id;
    }elseif(CA::join('cps','ca.cps_id','cps.id')->where('ca.user_id',$user_id)->where('cps.subject_id',$subject_id)->exists()){
        return CA::join('cps','ca.cps_id','cps.id')->where('ca.user_id',$user_id)->where('cps.subject_id',$subject_id)->first()->cps_id;
    }else{
        return -1;
    }
}

function getSubjectHours($user_id,$subject_id,$lush_id=null,$definedHour=45){
    //Kshyre ndreqi per ushtrime nese ka shume grupe caktoje kohen sakte per secilin grup
    $cps_id = getCPS($user_id,$subject_id);
    $data = CP::with('ca')->whereId($cps_id)->get()->toArray()[0];
    $semester = getSemesterFromSubject($data['subject_id']);

    $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
    whereUserId($user_id)->whereSubjectId($subject_id)->
    whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

    $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(schedule.end,schedule.start))) as diff'))->
    join('ca','schedule.user_id','ca.user_id')->where('ca.cps_id',$cps_id)->
    where('schedule.subject_id',$subject_id)->
    where('schedule.to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

    if($lush_id !== null){
        if(LUSH::find($lush_id)->lush == "Ligjëratë"){
            return [$data['lecture_hours']-$lecture_hours];
        }else{
            return [$data['exercise_hours']-$exercise_hours];
        }
    }else{
        return [$data['lecture_hours']-$lecture_hours,$data['exercise_hours']-$exercise_hours];
    }
}

//funksioni qe e kontrollon nese kjo lende ka kryer oret
function hasSubjectFinished($user_id,$subject_id){
    $cps_id = getCPS($user_id,$subject_id);

    $data = CP::with('ca')->whereId($cps_id)->get()->toArray()[0];

    $semester = getSemesterFromSubject($subject_id);

    $lush_id = getLush($user_id,$subject_id);

    if(LUSH::find($lush_id)->lush == "Ligjëratë"){
        $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
            whereUserId($user_id)->whereSubjectId($subject_id)->
            whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        return ($data['lecture_hours']-$lecture_hours) <= 0;
    }else{
        $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
            where('user_id',$user_id)->
            where('lush_id',$lush_id)->
            where('subject_id',$subject_id)->
            where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        $temp = CP::select('cps.exercise_hours')->join('ca', 'cps.id', 'ca.cps_id')->where('ca.user_id', $user_id)->where('cps.subject_id', $subject_id)->first()['exercise_hours'];
        $hoursPerAssistant = null;
        if(getNumberOfAssistants(getCps($user_id,$subject_id)) == 1){
            $hoursPerAssistant = floor(($temp-$exercise_hours)/2);
        }else{
            $hoursPerAssistant = floor(($temp-$exercise_hours)/getNumberOfAssistants(getCps($user_id,$subject_id)));
        }

        return $hoursPerAssistant <= 0;
    }
}

//A kane mbaru oret per lenden dhe lush e caktuar
function hasSubjectHours($user_id,$subject_id,$lush_id){
    //Merre cps_idn
    $cps_id = getCPS($user_id,$subject_id);

    //Krejt te dhenat per ate cps_id
    $data = CP::with('ca')->whereId($cps_id)->first()->toArray();

    //Merr semestrin
    $semester = getSemesterFromSubject($subject_id);

    if(LUSH::find($lush_id)->lush == "Ushtrime"){
        //Oret e ushtrimeve
        $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
        where('user_id',$user_id)->
        where('lush_id',$lush_id)->
        where('subject_id',$subject_id)->
        where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        return ($data['exercise_hours']-$exercise_hours) >= 1;
    }else{
        //Oret e ligjeratave ose tjeter
        $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
        whereUserId($user_id)->
        whereSubjectId($subject_id)->
        whereLushId($lush_id)->
        whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        return ($data['lecture_hours']-$lecture_hours) >= 1;
    }
}

function hasSubjectLush($subject_id,$lush_id){
    return SubjectLush::whereSubjectId($subject_id)->whereLushId($lush_id)->exists();
}

function hasSubjectLushGroup($subject_id,$lush_id,$group_id){
    $subject_lush_id = SubjectLush::whereSubjectId($subject_id)->whereLushId($lush_id)->first()->id;
    return GroupsLushSubject::whereSubjectLushId($subject_lush_id)->whereGroupId($group_id)->exists();
}

function hasSubjectTeacher($subject_id,$user_id){
    $hasCpTeacher = CP::whereSubjectId($subject_id)->whereUserId($user_id)->exists();
    $hasCaTeacher = Ca::select('cps.subject_id','ca.user_id')->join('cps','ca.cps_id','cps.id')->where('cps.subject_id',$subject_id)->where('ca.user_id',$user_id)->exists();

    return ($hasCpTeacher || $hasCaTeacher);
}

//Merr sallat per fakultetin e dekanit te loguar
function getHalls(){
    $faculty = explode('_',user()->roles()->first()->slug)[1];

    $data = Hall::select('halls.id')->
    join('faculties','halls.faculty_id','faculties.id')->
    leftJoin('faculties as fa','halls.sec_faculty_id','fa.id')->
    join('halltypes','halls.halltype_id','halltypes.id')->
    where(function($query) use($faculty){
        $query->where('faculties.faculty',$faculty);
        $query->orWhere('fa.faculty',$faculty);
    })->pluck('halls.id')->toArray();

    return $data;
    //Array ( [0] => Array ( [id] => 2 [hallType] => Kabinet ) [1] => Array ( [id] => 1 [hallType] => Ligjëratë ) )
}

function getPossibleTime(){
    $temp = array();
    $startScheduleTime = settings()['start_schedule_time'];
    $endScheduleTime = settings()['end_schedule_time'];

    $hours = Carbon::parse($endScheduleTime)->diffInHours(Carbon::parse($startScheduleTime));
    for($i = 0; $i <= $hours * 2; $i++){
        if($i != 0){
            $temp[] = Carbon::parse($temp[$i-1])->addMinutes(30)->toTimeString();
        }else{
            $temp[] = Carbon::parse($startScheduleTime)->toTimeString();
        }
    }
    return $temp;
}

function getDays(){
    return ['2017-04-17','2017-04-18','2017-04-19','2017-04-20','2017-04-21','2017-04-22'];
}

function getUpperAndLowerBorder($all_posibles, $not_available_index,$timeOrDay){
    $lower = null;
    $upper = null;
    for ($i=$not_available_index-1; $i >= 0 ; $i--) {
        if($timeOrDay == 'time'){
            if(count($all_posibles[$i]) == 6 ){
                $lower[0] = $i;
                $lower[1] = ($not_available_index-1) - $i;
            }
        }else{
            if(count($all_posibles[$i]) == 2 ){
                $lower[0] = $i;
                $lower[1] = ($not_available_index-1) - $i;
            }
        }
    }

    for($i=$not_available_index+1; $i < count($all_posibles); $i++){
        if($timeOrDay == 'time'){
            if(count($all_posibles[$i]) == 6){
                $upper[0] = $i;
                $upper[1] = (count($all_posibles)-1) - ($not_available_index+1);
            }
        }else{
            if(count($all_posibles[$i]) == 2){
                $upper[0] = $i;
                $upper[1] = (count($all_posibles)-1) - ($not_available_index+1);
            }
        }
    }

    if($lower != null && $upper != null){
        return [$lower,$upper];
    }elseif($lower != null && $upper == null){
        return [$lower, [$lower[0],count($all_posibles)-1]];
    }else{
        return [[$upper[0],count($all_posibles)-1],$upper];
    }
}

function dayCost($user_id,$daySelected_index){
    $availability = getTeacherAvailability($user_id);
    $allPosible = getDays();

    if($availability['not_allowed'] == null){
        //nese mesimdhenesi i caktuar nuk ka ore qe nuk mundet te vije
        return 0;
    }else{
        $allDay_notAllowed = array();
        //Merri ditet kur mesimdhenesi nuk mund te vije fare
        foreach($availability['not_allowed'] as $not_allowed){
            if($not_allowed['TIME(start)'] == '08:00:00' && $not_allowed['TIME(end)'] == '20:00:00'){
                $allDay_notAllowed[] = $not_allowed['DATE(start)'];
                foreach($allPosible as $key=>$day){
                    if($day == $not_allowed['DATE(start)']){
                        $date = $allPosible[$key];
                        $allPosible[$key] = [$date,0];
                    }
                }
            }
        }

        if($allDay_notAllowed == null){
            return 0;
        }

        $LAUB = getUpperAndLowerBorder($allPosible, $daySelected_index,'day');

        return ($LAUB[0][1] + $LAUB[1][1])/2;
    }
}

function timeCost($user_id,$timeSelected_index){
    $availability = getTeacherAvailability($user_id);
    $allPosibleTimes = getPossibleTime();

    if($availability['not_allowed'] == null){
        //nese mesimdhenesi i caktuar nuk ka ore qe nuk mundet te vije
        return 0;
    }else{
        //Ditet kur mesimdhenesi nuk mund te vije fare
        $newPosibilities = array();

        foreach($availability['not_allowed'] as $not_allowed){
            if(Carbon::parse($allPosibleTimes[$timeSelected_index])->between(Carbon::parse($not_allowed['TIME(start)']),Carbon::parse($not_allowed['TIME(end)']))){
                for($i = array_search($not_allowed['TIME(start)'],$allPosibleTimes); $i < array_search($not_allowed['TIME(end)'],$allPosibleTimes);$i++){
                    $newPosibilities[$i][] = $not_allowed['DATE(start)'];
                }
            }
        }

        $isAvailable = true;
        foreach($newPosibilities as $posibility){
            if(count($posibility) == 6){
                $isAvailable = false;
            }
        }

        if($isAvailable){
            return 0;
        }else{
            $LAUB = getUpperAndLowerBorder($newPosibilities, $timeSelected_index,'time');
            return ($LAUB[0][1] + $LAUB[1][1])/5;
        }
    }
}

function groupBy_SameKey($unstructured_matrix,$k){
    $temp = array();
    foreach($unstructured_matrix as $key=>$element){
        $temp[(int)$element[$k]][] = $element;
    }
    return $temp;
}

//Shiko nese ka mundesi per marrje te produktit kartezian
function TDHCExists($currentTDHCartesian, $TDH){
    foreach($currentTDHCartesian as $key=>$element){
        if($element == $TDH){
            return $key;
        }
    }
    return -1;
}

//funksioni qe e kontrollon nese kjo lende ka kryer oret
function hasSubjectFinishedCPS($cps_id,$lush_id = null){
    $data = CP::with('ca')->whereId($cps_id)->get()->toArray()[0];

    $semester = getSemesterFromSubject($data['subject_id']);
    if($lush_id == null){
        $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
            whereUserId($data['user_id'])->whereSubjectId($data['subject_id'])->
            whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        $lecturedFinished = ($data['lecture_hours']-$lecture_hours) <= 0;

        foreach($data['ca'] as $ca){
            $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
                where('user_id',$ca['user_id'])->
                where('subject_id',$data['subject_id'])->
                where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

            $exerciseFinished[] = ($data['exercise_hours']-$exercise_hours) <= 0;
        }

        return [($lecturedFinished && array_true($exerciseFinished)),$lecturedFinished,array_true($exerciseFinished)];
    }elseif(LUSH::find($lush_id)->lush == "Ligjëratë"){
        $lecture_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
            whereUserId($data['user_id'])->whereSubjectId($data['subject_id'])->
            whereTo((!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

        $lecturedFinished = ($data['lecture_hours']-$lecture_hours) <= 0;
    }else{
        foreach($data['ca'] as $ca){
            $exercise_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->
                where('user_id',$ca['user_id'])->
                where('subject_id',$data['subject_id'])->
                where('to', (!getSeasonFromSemester($semester)) ? settings()['end_winter_semester'] : settings()['end_summer_semester'])->get()->toArray()[0]['diff']/2700;

            $exerciseFinished[] = ($data['exercise_hours']-$exercise_hours) <= 0;
        }
    }
}

function getDurationFromCPSLush($cps_id, $lush_id, $allPosibleDurations){
    $maxDuration = 2;
    if(Lush::find($lush_id)->lush == "Ligjëratë"){
        $maxDuration = CP::find($cps_id)->lecture_hours;
    }else{
        $maxDuration = CP::find($cps_id)->exercise_hours;
    }
    $temp = array();

    foreach($allPosibleDurations as $key=>$duration){
        if($duration <= $maxDuration){
            $temp[] = $key;
        }
    }

    return $temp[array_rand($temp)];
}
