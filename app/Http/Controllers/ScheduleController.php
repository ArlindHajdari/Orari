<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Hall;
use App\Models\LushCpa;
use App\Models\CP;
use App\Models\Setting;
use App\Models\Lush;
use App\Models\Schedule;
use App\Models\Availability;
use App\Models\Subject;
use App\Models\SubjectLush;
use App\Models\StatusAcademicTitle;
use App\PHPLinq\PHPLinq\LinqToObjects;
use ErrorException;
use Sentinel;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use App\Helper\GeneticAlgorithm;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('Menaxho.Orari.scheduler');
    }

    public function getLendetFromSemester(Request $request)
    {
        try {
            $data = null;
            $fakulteti = explode('_', user()->roles()->first()->name)[1];
            DB::enableQueryLog();
            $data = Cp::select(DB::raw('concat(subjects.id,"-",cps.id) as id'), 'subjects.subject')->
            join('subjects', 'cps.subject_id', 'subjects.id')->
            join('departments', 'subjects.department_id', 'departments.id')->
            join('faculties', 'departments.faculty_id', 'faculties.id')->
            where('faculties.faculty', $fakulteti)->where(function ($query) use ($request) {
                if ($request->semester == 0) {
                    $query->orWhere(DB::raw('mod(subjects.semester,2)'), 1);
                } else {
                    $query->orWhere(DB::raw('mod(subjects.semester,2)'), 0);
                }
            })->pluck('subject', 'id')->toArray();

            $new_data = array();
            foreach($data as $id=>$value){
                if(!hasSubjectFinishedCPS(explode('-',$id)[1])[0]){
                    $new_data[$id] = $value;
                }
            }


            return response()->json($new_data, 200);
        } catch (QueryException $e) {
            return;
        }
    }

    public function getProfByLUSHandSubject(Request $request)
    {
        DB::enableQueryLog();
        $lush = Lush::select('lush')->where('id', $request->lush_id)->first()['lush'];
        if ($lush == 'Ligjëratë') {
            $prof = User::select(DB::raw("CONCAT(academic_titles.academic_title,' ',users.first_name,' ',users.last_name) AS fullname"), 'users.id')->join('cps', 'users.id', 'cps.user_id')->join('cpas', 'users.cpa_id', 'cpas.id')->join('lush_cpa', 'cpas.id', 'lush_cpa.cpa_id')->join('lush', 'lush_cpa.lush_id', 'lush.id')->join('academic_titles', 'users.academic_title_id', 'academic_titles.id')->where('cps.subject_id', explode('-', $request->subject_id)[0])->where('lush.id', $request->lush_id)->where('cps.id', explode('-', $request->subject_id)[1])->pluck('fullname', 'id')->toArray();
        } elseif ($lush == 'Ushtrime') {
            $prof = User::select(DB::raw("CONCAT(academic_titles.academic_title,' ',users.first_name,' ',users.last_name) AS fullname"), 'users.id')->join('ca', 'users.id', 'ca.user_id')->join('cps', 'ca.cps_id', 'cps.id')->join('cpas', 'users.cpa_id', 'cpas.id')->join('lush_cpa', 'cpas.id', 'lush_cpa.cpa_id')->join('lush', 'lush_cpa.lush_id', 'lush.id')->join('academic_titles', 'users.academic_title_id', 'academic_titles.id')->where('cps.subject_id', explode('-', $request->subject_id)[0])->where('ca.cps_id', explode('-', $request->subject_id)[1])->pluck('fullname', 'id')->toArray();
        }
        return response()->json($prof, 200);
    }

    public function getGroupByLende(Request $request)
    {
        $lecture_hours = 0;

        $lush = Lush::select('lush')->where('id', $request->lush_id)->first()['lush'];

        if ($lush == 'Ligjëratë') {
            $lecture_hours = CP::select('lecture_hours')->where('user_id', $request->prof_id)->where('subject_id', explode('-', $request->subject_id)[0])->first()['lecture_hours'];
        } elseif ($lush == 'Ushtrime') {
            $lecture_hours = CP::select('cps.exercise_hours')->join('ca', 'cps.id', 'ca.cps_id')->where('ca.user_id', $request->prof_id)->where('cps.subject_id', explode('-', $request->subject_id)[0])->first()['exercise_hours'];
        }

        //Supozohet se grupet regjistrohen me rend
        $group = Group::select('group', 'id')->where('id', '<=', $lecture_hours)->pluck('group', 'id')->toArray();

        return response()->json($group, 200);
    }

    public function getlushByLende(Request $request)
    {
        $data = SubjectLush::select('lush.lush', 'lush.id')->
        join('lush', 'subject_lush.lush_id', 'lush.id')->
        where('subject_lush.subject_id',explode('-',$request->id)[0])->
        pluck('lush', 'id')->toArray();

        $dataToBeSent = array();
        foreach($data as $key=>$value){
            if($value == 'Ligjëratë'){
                if(!hasSubjectFinishedCPS(explode('-',$request->id)[1])[1])
                    $dataToBeSent[$key] = $value;
            }else{
                if(!hasSubjectFinishedCPS(explode('-',$request->id)[1])[2])
                    $dataToBeSent[$key] = $value;
            }
        }

        return response()->json($dataToBeSent, 200);
    }

    public function create()
    {
        //
    }

    public function generateScheduler(Request $request)
    {
        $message = [
            'semester.required' => 'Ju lutem zgjedhni semestrin per te vazhduar!',
            'semester.numeric' => 'Semestri duhet te jete numer per te vazhduar!',
            'semester.min' => 'Semestri duhet te jete se paku :min'
        ];

        $validator = Validator::make($request->all(), [
            'semester'=>'required|numeric|min:1',
            'genetics' =>'required|in:genetics,linear'
        ], $message);

        if ($validator->fails()) {
            return response()->json([
                'server'=>false,
                'msg' => $validator->errors()->first('semester')
            ], 400);
        }

        if($request->genetics == 'genetics'){
            try{
                set_time_limit(120);
                $mutation = ($request->mutation != null || $request->mutation != 0.1) ? $request->mutation : 0.2;
                $crossover = ($request->crossover != null || $request->crossover != 0.1) ? $request->crossover : 0.25;
                $iteration = ($request->iteration != null || $request->iteration != 100) ? $request->iteration : 200;
                $GA = new GeneticAlgorithm($request->semester,$mutation,$crossover);

                $population = $GA->generateChromosomes();
                // dd($GA->getTeacherObjectiveFunction($population));

                $cartesian = $GA->getTimeDayHallCartesian();
                $cartesianCount = count($cartesian);
                $generationCount = 0;

                $maxGenerationStegnant = $iteration;
                $maxFitness = 0;

                $time1 = microtime(true);


                $badChromosomes = array();
                //Grupimi ne baze te Prof-Lende-Grup, per secilin grup ka 3 kromozome
                $setTeacherSubjectGrup = $GA->getGroupChromosomes($population);
                $bestChromosomesPerGroupi = array();
                //Per secilin grup te chromozomeve merri me te miret
                $bestChromosomesPerGroup = array();
                foreach($setTeacherSubjectGrup as $groupChromosomes){
                    $bestChromosomesPerGroup[] = $GA->getGroupFittest($groupChromosomes);
                }

                //Grupimi në bazë të mësimdhënësit
                $temp = groupBy_SameKey($bestChromosomesPerGroup,0);

                //Grupimi në bazë të lëndës
                $array = array();
                foreach($temp as $key=>$element){
                    $array[$key] = groupBy_SameKey($element,1);
                }

                //Grupimi në bazë të grupit
                $array1 = array();
                foreach($array as $key=>$element){
                    foreach($element as $key1=>$element1){
                        $array1[$key][$key1] = groupBy_SameKey($element1,4);
                    }
                }

                //Per secilin best chromosome te secilit grup shiko nese ka vend ne produktin kartezian te dites, kohes se fillimit dhe salles
                foreach($bestChromosomesPerGroup as $key=>$bestChromosome){
                    $temp = $bestChromosome;
                    $isGood = true;
                    $generationStegnant = 0;
//                    dump($GA->objectiveTeacherFunction($array1[(int)$temp[0]]));
                    //Perderisa nuk eshte i lejueshem qifti i dites, kohes se fillimit dhe salles

                    while(!($GA->isTDHCAvailable($cartesian, $temp[5], $temp[6], (int)$temp[3], $temp[7])) || ($GA->objectiveTeacherFunction($array1[(int)$temp[0]]) < 40)){
                        $generationStegnant++;
                        $chromosomesSetForGroup = $GA->getSetFromKey($setTeacherSubjectGrup, (int)$temp[0].'-'.(int)$temp[1].'-'.$temp[4]);

                        //Merr me te mirin si prind dhe random njerin prej dy te mbetureve dhe bej gati per qiftezim(crossover)
                        $parents = $GA->getParents($chromosomesSetForGroup, $generationStegnant);

                        $temp = $GA->uniformCrossover($parents);

//                        dump($array1[(int)$temp[0]][(int)$temp[1]][$temp[4]]);
                        $array1[(int)$temp[0]][(int)$temp[1]][$temp[4]][0] = $temp;

                        //Nese edhe pas crossoverit te best chromosomit me kromozomin e zgjedhur random pas shume stagnimeve nuk ka gjetur zgjidhje
                        if($generationStegnant > $maxGenerationStegnant || floor(microtime(true)-$time1) > 60){
                            $badChromosomes[$key] = $bestChromosome;
                            $isGood = false;
                            break;
                        }
                    }

                    if($isGood) {
                        $GA->updateTDHCartesian($cartesian, $temp[5], $temp[6], (int)$temp[3], $temp[7]);
                        $bestChromosomesPerGroupi[$key] = $temp;
                    }
                }

//                dd($bestChromosomesPerGroupi);
                //Shfaqja e perqindjes per secilin profesor
                $professors_percentage = $GA->getTeacherObjectiveFunction($array1);
                $tempua = array();
                foreach($professors_percentage as $key=>$percentage){
                    $tempua[] = [getTeacherFromId($key),$percentage];
                }

                $toBeInserted = $GA->getGroupChromosomes($array1);

                foreach($toBeInserted as $chromosome){
                    $start = $GA->days[$chromosome[0][5]].' '.$GA->startTime[$chromosome[0][6]];
                    $end = Carbon::parse($start)->addMinutes($GA->duration[$chromosome[0][7]]*45)->toDateTimeString();
                    storeSchedule($start,$end,(int)$chromosome[0][0],(int)$chromosome[0][1],$chromosome[0][4],(int)$chromosome[0][3],$chromosome[0][2],$request->semester);
                }

                $time2 = microtime(true);
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg'=>$tempua,
                    'genetic'=>true
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'server'=>true,
                    'title'=>'Gabim',
                    'msg'=>$e->getMessage()
                ], 400);
            }
        }elseif($request->genetics == 'linear'){
            set_time_limit(15);
            //Merri ID e CPS(Profesori, lenda edhe asistentet)
            $cps_ids = getCPSFromSemester($request->semester);

            //Krijo nje funksion qe kthen true nese koha e zgjedhur eshte ne mes nje kohe te caktuar tjeter ne menyre qe me tentu me e fut orarin
            //ne kohen kur profesori mundet me ardhe
            //loop through $cps_ids edhe per secilin relacion prof=>lende generateTimeDomain, prej rezultatit te generateTImeDmain zgjedh random nje kohe edhe
            //fute ne orar permes storeSchedule funksionit
            foreach ($cps_ids as $cps) {
                //Merr grupet varesisht se sa ore per ligjerate jane.
                $groups = (count(getGroupsL($cps->user_id, $cps->subject_id)) == 1) ? [null=>null] : getGroupsL($cps->user_id, $cps->subject_id);
                $hall_id = -1;
                $lush_id = -1;

                foreach ($groups as $key=>$group) {
                    $timeToBeChosen = generateTimeDomain($cps->user_id, $cps->subject_id, $key, $hall_id, $lush_id);

                    if(is_array($timeToBeChosen)){
                        storeSchedule($timeToBeChosen['start'], $timeToBeChosen['end'], $cps->user_id, $cps->subject_id, $key, $hall_id, $lush_id, $request->semester);
                    }
                }

                foreach ($cps->ca as $ca) {
                    $groups = (count(getGroupsL($ca->user_id, $cps->subject_id)) == 1) ? [null=>null] : getGroupsL($ca->user_id, $cps->subject_id);
                    $hall_id = -1;
                    $lush_id = -1;
                    foreach ($groups as $key=>$group) {
                        $timeToBeChosen = generateTimeDomain($ca->user_id, $cps->subject_id, $key, $hall_id, $lush_id);
                        
                        if(is_array($timeToBeChosen)){
                            storeSchedule($timeToBeChosen['start'], $timeToBeChosen['end'], $ca->user_id, $cps->subject_id, $key, $hall_id, $lush_id, $request->semester);
                        }
                    }
                }
            }
            return response()->json([
                'success'=>true,
                'title'=>'Sukses',
                'msg'=>'Gjenerimi përfundoi me sukses!',
                'genetic'=>false
            ], 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start' => 'bail|required|date',
                'end' => 'bail|required|date',
                'semestri' => 'bail|required',
                'user_id' => 'bail|required|numeric|exists:users,id',
                'hall_id' => 'bail|required|numeric|exists:halls,id',
                'group_id' => 'bail|numeric',
                'lush_id' => 'bail|required|numeric|exists:lush,id',
                'subject_id' => 'bail|required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'fails' => true,
                    'title' => 'Gabim gjatë validimit',
                    'msg' => array_values($validator->getMessageBag()->toArray())[0]
                ], 400);
            }
            $settings = Setting::all()->toArray()[0];

            $lectureOrExercise_Hours = null;
            $hours_per_day = null;

            if (Lush::find($request->lush_id)->lush == 'Ligjëratë') {
                $lectureOrExercise_Hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                $hours_per_day = $settings['max_hour_day_lecture'];
            } else {
                $lectureOrExercise_Hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                $hours_per_day = $settings['max_hour_day_exercise'];
            }
            $semester = Subject::select('semester')->where('id', explode('-', $request->subject_id)[0])->get()->toArray()[0]['semester'];

            $inside_semester_boundaries = ($request->semester == "false") ? (Setting::where(function ($query) use ($request) {
                $query->where('start_winter_semester', '<', Carbon::now()->toDateString());
                $query->where('start_winter_semester', '<', Carbon::now()->toDateString());
                $query->where('end_winter_semester', '>', Carbon::now()->toDateString());
                $query->where('end_winter_semester', '>', Carbon::now()->toDateString());
            })->exists()) : (Setting::where(function ($query) use ($request) {
                $query->where('start_summer_semester', '<', Carbon::now()->toDateString());
                $query->where('start_summer_semester', '<', Carbon::now()->toDateString());
                $query->where('end_summer_semester', '>', Carbon::now()->toDateString());
                $query->where('end_summer_semester', '>', Carbon::now()->toDateString());
            })->exists());

            //nese ekziston orar per profesorin e dhene
            if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('subject_id', explode('-', $request->subject_id)[0])->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'))->where('user_id', $request->user_id)->exists()) {
                if (!Schedule::select('user_id')->where(function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                    });
                    $query->orWhere(function ($query) use ($request) {
                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                        $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                    });
                    $query->orWhere(function ($query) use ($request) {
                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                    });
                })->where(function ($query) use ($request,$settings) {
                    $query->where('user_id', $request->user_id);
                    $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                    $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                })->exists()) {
                    //nese koha qe profesori ka orar eshte me e vogel ose e barabarte me kohen e lejuar per ore
                    if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('subject_id', explode('-', $request->subject_id)[0])->where('user_id', $request->user_id)->get()->toArray()[0]['diff'] / 2700 <= $lectureOrExercise_Hours) {
                        //Nese data e selektuar eshte brenda domenit te semestrit
                        if ($inside_semester_boundaries) {
                            //kur profesori nuk ka te dhena ne orar per daten e selektuar (start,end)
                            //nese grupi eshte i pergjithshem
                            if ($request->group_id == 0) {
                                //nese grupi nuk eshte i nxënë
                                if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                    $query->where(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    });
                                })->where(function ($query) use ($semester,$request,$settings) {
                                    $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                    $query->where('subjects.semester', $semester);
                                    $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                    $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                })->exists()) {
                                    //regjistrimi ne orar
                                    $schedule = new Schedule;
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $request->user_id;
                                    $schedule->hall_id = $request->hall_id;
                                    $schedule->subject_id = explode('-', $request->subject_id)[0];
                                    $schedule->lush_id = $request->lush_id;
                                    //caktimi i kohes se shfaqjes se orarit, duke u bazuar ne kohen e fillimi dhe
                                    // mbarimit te eventit
                                    $schedule->from = Carbon::now()->toDateString();
                                    $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                                    if ($schedule->save()) {
                                        $max_hours_limit = null;
                                        if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                            $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                        } else {
                                            $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                        }

                                        if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                            $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                            $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                        } else {
                                            $max_hours_limit = $max_hours * 45;
                                        }
                                        //nese eshte regjistru orari me sukses
                                        $new_schedule = Schedule::find($schedule->id);
                                        return response()->json([
                                            'id' => $new_schedule->id,
                                            'start' => $new_schedule->start,
                                            'end' => $new_schedule->end,
                                            'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0],
                                            'max_hours' => $max_hours_limit
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'fails' => true,
                                        ], 400);
                                    }
                                } else {
                                    //nese grupi eshte i nxene
                                    return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                                }
                            } else {
                                //kur grupi nuk eshte i pergjithshem
                                if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                    $query->where(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    });
                                })->where(function ($query) use ($request) {
                                    $query->where('group_id', $request->group_id);
                                    $query->orWhere('group_id', null);
                                })->where(function ($query) use ($semester,$request,$settings) {
                                    $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                    $query->where('subjects.semester', $semester);
                                    $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                    $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                })->exists()) {
                                    $schedule = new Schedule;
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $request->user_id;
                                    $schedule->hall_id = $request->hall_id;
                                    $schedule->subject_id = explode('-', $request->subject_id)[0];
                                    $schedule->lush_id = $request->lush_id;
                                    $schedule->group_id = $request->group_id;
                                    $schedule->from = Carbon::now()->toDateString();
                                    $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                                    if ($schedule->save()) {
                                        $max_hours_limit = null;
                                        if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                            $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                        } else {
                                            $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                        }

                                        if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                            $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                            $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                        } else {
                                            $max_hours_limit = $max_hours * 45;
                                        }

                                        $new_schedule = Schedule::find($schedule->id);
                                        return response()->json([
                                            'id' => $new_schedule->id,
                                            'start' => $new_schedule->start,
                                            'end' => $new_schedule->end,
                                            'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . ' ' . $new_schedule->user->last_name . ' - ' . $new_schedule->subject->subject . ' - ' . $new_schedule->lush->lush[0] . " - "
                                                . $new_schedule->group->group,
                                            'max_hours' => $max_hours_limit
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'fails' => true,
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                                }
                            }
                        } else {
                            //jasht kufijve te semestrit
                            //nese grupi eshte i pergjithshem
                            if ($request->group_id == 0) {
                                //nese grupi nuk eshte i nxënë
                                if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                    $query->where(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    });
                                })->where(function ($query) use ($semester,$request,$settings) {
                                    $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                    $query->where('subjects.semester', $semester);
                                    $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                    $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                })->exists()) {
                                    //regjistrimi ne orar
                                    $schedule = new Schedule;
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $request->user_id;
                                    $schedule->hall_id = $request->hall_id;
                                    $schedule->subject_id = explode('-', $request->subject_id)[0];
                                    $schedule->lush_id = $request->lush_id;
                                    //caktimi i kohes se shfaqjes se orarit, duke u bazuar ne kohen e fillimi dhe
                                    // mbarimit te eventit
                                    $schedule->from = ($request->semestri == "0") ? $settings['start_winter_semester'] : $settings['start_summer_semester'];
                                    $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                                    if ($schedule->save()) {
                                        $max_hours_limit = null;
                                        if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                            $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                        } else {
                                            $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                        }

                                        if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                            $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;
                                            $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                        } else {
                                            $max_hours_limit = $max_hours * 45;
                                        }
                                        //nese eshte regjistru orari me sukses
                                        $new_schedule = Schedule::find($schedule->id);
                                        return response()->json([
                                            'id' => $new_schedule->id,
                                            'start' => $new_schedule->start,
                                            'end' => $new_schedule->end,
                                            'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0],
                                            'max_hours' => $max_hours_limit
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'fails' => true,
                                        ], 400);
                                    }
                                } else {
                                    //nese grupi eshte i nxene
                                    return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                                }
                            } else {
                                //kur grupi nuk eshte i pergjithshem
                                if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                    $query->where(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                    });
                                    $query->orWhere(function ($query) use ($request) {
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                        $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                        $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    });
                                })->where(function ($query) use ($request) {
                                    $query->where('group_id', $request->group_id);
                                    $query->orWhere('group_id', null);
                                })->where(function ($query) use ($semester,$request,$settings) {
                                    $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                    $query->where('subjects.semester', $semester);
                                    $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                    $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                })->exists()) {
                                    $schedule = new Schedule;
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $request->user_id;
                                    $schedule->hall_id = $request->hall_id;
                                    $schedule->subject_id = explode('-', $request->subject_id)[0];
                                    $schedule->lush_id = $request->lush_id;
                                    $schedule->group_id = $request->group_id;
                                    $schedule->from = ($request->semestri == "0") ? $settings['start_winter_semester'] : $settings['start_summer_semester'];
                                    $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                                    if ($schedule->save()) {
                                        $max_hours_limit = null;
                                        if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                            $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                        } else {
                                            $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                        }

                                        if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                            $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                            $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                        } else {
                                            $max_hours_limit = $max_hours * 45;
                                        }

                                        $new_schedule = Schedule::find($schedule->id);
                                        return response()->json([
                                            'id' => $new_schedule->id,
                                            'start' => $new_schedule->start,
                                            'end' => $new_schedule->end,
                                            'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . ' ' . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0] . " - "
                                                . $new_schedule->group->group,
                                            'max_hours' => $max_hours_limit
                                        ], 200);
                                    } else {
                                        return response()->json([
                                            'fails' => true,
                                        ], 400);
                                    }
                                } else {
                                    return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                                }
                            }
                        }
                    } else {
                        return response()->json([
                            'fails' => true,
                            'title' => 'Lajmërim',
                            'msg' => 'Profesori i caktuar ka kaluar oret e lejuara per kete dite!'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'fails' => true,
                        'title' => 'Lajmërim',
                        'msg' => 'Mësimdhënësi është i nxënë në këtë kohë!'
                    ], 400);
                }
            } else {
                //kur profesori nuk ka te dhena ne orar per daten e selektuar (start,end)
                //brenda semestrit
                if ($inside_semester_boundaries) {
                    //nese grupi eshte i pergjithshem
                    if ($request->group_id == 0) {
                        //nese grupi nuk eshte i nxënë
                        if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                            $query->where(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                            });
                        })->where(function ($query) use ($semester,$request,$settings) {
                            $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                            $query->where('subjects.semester', $semester);
                            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                            $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                        })->exists()) {
                            //regjistrimi ne orar
                            $schedule = new Schedule;
                            $schedule->start = $request->start;
                            $schedule->end = $request->end;
                            $schedule->user_id = $request->user_id;
                            $schedule->hall_id = $request->hall_id;
                            $schedule->subject_id = explode('-', $request->subject_id)[0];
                            $schedule->lush_id = $request->lush_id;
                            //caktimi i kohes se shfaqjes se orarit, duke u bazuar ne kohen e fillimi dhe
                            // mbarimit te eventit
                            $schedule->from = Carbon::now()->toDateString();
                            $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] :
                                $settings['end_summer_semester'];

                            if ($schedule->save()) {
                                $max_hours_limit = null;
                                if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                    $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                } else {
                                    $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                }

                                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                    $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                    $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                } else {
                                    $max_hours_limit = $max_hours * 45;
                                }
                                //nese eshte regjistru orari me sukses
                                $new_schedule = Schedule::find($schedule->id);
                                return response()->json([
                                    'id' => $new_schedule->id,
                                    'start' => $new_schedule->start,
                                    'end' => $new_schedule->end,
                                    'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0],
                                    'max_hours' => $max_hours_limit
                                ], 200);
                            } else {
                                return response()->json([
                                    'fails' => true,
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'fails' => true,
                            ], 400);
                        }
                    } else {
                        //kur grupi nuk eshte i pergjithshem
                        //kur grupi nuk eshte i nxene
                        if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                            $query->where(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                            });
                        })->where(function ($query) use ($request) {
                            $query->where('group_id', $request->group_id);
                            $query->orWhere('group_id', null);
                        })->where(function ($query) use ($semester,$request,$settings) {
                            $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                            $query->where('subjects.semester', $semester);
                            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                            $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                        })->exists()) {
                            $schedule = new Schedule;
                            $schedule->start = $request->start;
                            $schedule->end = $request->end;
                            $schedule->user_id = $request->user_id;
                            $schedule->hall_id = $request->hall_id;
                            $schedule->subject_id = explode('-', $request->subject_id)[0];
                            $schedule->lush_id = $request->lush_id;
                            $schedule->group_id = $request->group_id;
                            $schedule->from = Carbon::now()->toDateString();
                            $schedule->to = ($request->semestri == "false") ? $settings['end_winter_semester'] :
                                $settings['end_summer_semester'];

                            if ($schedule->save()) {
                                $max_hours_limit = null;
                                if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                    $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                } else {
                                    $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                }

                                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                    $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                    $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                } else {
                                    $max_hours_limit = $max_hours * 45;
                                }
                                $new_schedule = Schedule::find($schedule->id);
                                return response()->json([
                                    'id' => $new_schedule->id,
                                    'start' => $new_schedule->start,
                                    'end' => $new_schedule->end,
                                    'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0] . " - "
                                        . $new_schedule->group->group,
                                    'max_hours' => $max_hours_limit
                                ], 200);
                            } else {
                                return response()->json([
                                    'fails' => true,
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'fails' => true,
                                'title' => 'Gabim me grupin',
                                'msg' => 'Grupi është i nxënë në këtë kohë!'
                            ], 400);
                        }
                    }
                } else {
                    //kur jem jasht kufijve te semestrit
                    //nese grupi eshte i pergjithshem
                    if ($request->group_id == 0) {
                        //nese grupi nuk eshte i nxënë
                        if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                            $query->where(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                            });
                        })->where(function ($query) use ($semester,$request,$settings) {
                            $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                            $query->where('subjects.semester', $semester);
                            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                            $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                        })->exists()) {
                            //regjistrimi ne orar
                            $schedule = new Schedule;
                            $schedule->start = $request->start;
                            $schedule->end = $request->end;
                            $schedule->user_id = $request->user_id;
                            $schedule->hall_id = $request->hall_id;
                            $schedule->subject_id = explode('-', $request->subject_id)[0];
                            $schedule->lush_id = $request->lush_id;
                            //caktimi i kohes se shfaqjes se orarit, duke u bazuar ne kohen e fillimi dhe
                            // mbarimit te eventit
                            $schedule->from = ($request->semestri == "0") ? $settings['start_winter_semester'] : $settings['start_summer_semester'];
                            $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                            if ($schedule->save()) {
                                $max_hours_limit = null;
                                if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                    $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                } else {
                                    $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                }

                                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                    $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                    $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                } else {
                                    $max_hours_limit = $max_hours * 45;
                                }
                                //nese eshte regjistru orari me sukses
                                $new_schedule = Schedule::find($schedule->id);
                                return response()->json([
                                    'id' => $new_schedule->id,
                                    'start' => $new_schedule->start,
                                    'end' => $new_schedule->end,
                                    'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0],
                                    'max_hours' => $max_hours_limit
                                ], 200);
                            } else {
                                return response()->json([
                                    'fails' => true,
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'fails' => true,
                            ], 400);
                        }
                    } else {
                        //kur grupi nuk eshte i pergjithshem
                        //kur grupi nuk eshte i nxene
                        if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                            $query->where(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                            });
                            $query->orWhere(function ($query) use ($request) {
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                            });
                        })->where(function ($query) use ($request) {
                            $query->where('group_id', $request->group_id);
                            $query->orWhere('group_id', null);
                        })->where(function ($query) use ($semester,$request,$settings) {
                            $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                            $query->where('subjects.semester', $semester);
                            $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                            $query->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                        })->exists()) {
                            $schedule = new Schedule;
                            $schedule->start = $request->start;
                            $schedule->end = $request->end;
                            $schedule->user_id = $request->user_id;
                            $schedule->hall_id = $request->hall_id;
                            $schedule->subject_id = explode('-', $request->subject_id)[0];
                            $schedule->lush_id = $request->lush_id;
                            $schedule->group_id = $request->group_id;
                            $schedule->from = ($request->semestri == "0") ? $settings['start_winter_semester'] : $settings['start_summer_semester'];
                            $schedule->to = ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];

                            if ($schedule->save()) {
                                $max_hours_limit = null;
                                if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                                    $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                                } else {
                                    $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                                }

                                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                                    $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('schedule.to', ($request->semestri == "0") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 60;

                                    $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                                } else {
                                    $max_hours_limit = $max_hours * 45;
                                }
                                $new_schedule = Schedule::find($schedule->id);
                                return response()->json([
                                    'id' => $new_schedule->id,
                                    'start' => $new_schedule->start,
                                    'end' => $new_schedule->end,
                                    'title' => $new_schedule->user->academic_title->academic_title . $new_schedule->user->first_name . " " . $new_schedule->user->last_name . "\n" . $new_schedule->subject->subject . "\n" . $new_schedule->lush->lush[0] . " - "
                                        . $new_schedule->group->group,
                                    'max_hours' => $max_hours_limit
                                ], 200);
                            } else {
                                return response()->json([
                                    'fails' => true,
                                ], 400);
                            }
                        } else {
                            return response()->json([
                                'fails' => true,
                                'title' => 'Gabim me grupin',
                                'msg' => 'Grupi është i nxënë në këtë kohë!'
                            ], 400);
                        }
                    }
                }
            }
        } catch (QueryException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server!',
                'msg' => $e->getMessage()
            ], 500);
        } catch (ErrorException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server!',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function getMaxHourPerDay(Request $request)
    {
        try {
            $minutes_day_schedule = null;
            $max_minutes_limit = null;
            $semester_end = null;

            if ($request->semester == "false") {
                $semester_end = Setting::select('end_winter_semester')->get()->toArray()[0]['end_winter_semester'];
            } else {
                $semester_end = Setting::select('end_summer_semester')->get()->toArray()[0]['end_summer_semester'];
            }

            if (Lush::select('lush')->where('id', $request->lush_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                $max_hours_per_day = Setting::select('max_hour_day_lecture')->get()->toArray()[0]['max_hour_day_lecture'];
            } else {
                $max_hours_per_day = Setting::select('max_hour_day_exercise')->get()->toArray()[0]['max_hour_day_exercise'];
            }

            if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where(DB::raw('DATE(start)'), Carbon::parse($request->start)->toDateString())->exists()) {
                $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->user_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('to', $semester_end)->where(DB::raw('DATE(start)'), Carbon::parse($request->start)->toDateString())->get()->toArray()[0]['diff'] / 60;
                $max_minutes_limit = ($max_hours_per_day * 45) - $minutes_schedule;
            } else {
                $max_minutes_limit = $max_hours_per_day * 45;
            }

            $status_id = User::select('status_id')->where('id', $request->user_id)->get()->toArray()[0]['status_id'];
            $academic_title_id = User::find($request->user_id)->academic_title_id;
            $prof_hasLecture = false;

            if (StatusAcademicTitle::where('academic_title_id', $academic_title_id)->where('status_id', $status_id)->exists()) {
                $allowed_minutes = StatusAcademicTitle::select(DB::raw('SUM(normal_hours+extra_hours) as hours'))->where('academic_title_id', $academic_title_id)->where('status_id', $status_id)->get()->toArray()[0]['hours'] * 45;

                $prof_minutes = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('to', $semester_end)->where('user_id', $request->user_id)->get()->toArray()[0]['diff'] / 60;

                if ($prof_minutes < $allowed_minutes) {
                    $prof_hasLecture = true;
                }
            } else {
                $prof_hasLecture = true;
            }
            $data_to_be_sent = array();
            $data_to_be_sent['max_minutes_limit'] = $max_minutes_limit;
            $data_to_be_sent['start'] = $request->start;
            $data_to_be_sent['end'] = $request->end;
            if ($prof_hasLecture) {
                return response()->json($data_to_be_sent, 200);
            } else {
                return response()->json([
                    'fails' => true
                ], 500);
            }
        } catch (ErrorException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $semester_end = null;
            $faculty = explode('_', Sentinel::getUser()->roles()->first()->name)[1];

            DB::enableQueryLog();
            if ($request->semester == "false") {
                $semester_end = Setting::select('end_winter_semester')->get()->toArray()[0]['end_winter_semester'];
            } else {
                $semester_end = Setting::select('end_summer_semester')->get()->toArray()[0]['end_summer_semester'];
            }

            $without_group = Schedule::select('schedule.id', DB::raw('concat(academic_titles.academic_title,users.first_name," ",users.last_name, "\n", subjects.subject," - ",left(lush.lush,1)) as title'), 'schedule.end', 'schedule.start', DB::raw('((fac.faculty = "'.$faculty.'") OR (faculties.faculty = "'.$faculty.'")) as editable'))->join('users', 'schedule.user_id', 'users.id')->join('academic_titles', 'users.academic_title_id', 'academic_titles.id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('halls', 'schedule.hall_id', 'halls.id')->join('faculties as fac', 'halls.faculty_id', 'fac.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->join('lush', 'schedule.lush_id', 'lush.id')->where(function ($query) use ($request) {
                $query->where('schedule.hall_id', $request->hall_id);
            })->whereNull('schedule.group_id')->where('schedule.to', $semester_end);

            $scheduler = Schedule::select('schedule.id', DB::raw('concat(academic_titles.academic_title,users.first_name," ",users.last_name, "\n", subjects.subject,"\n",left(lush.lush,1)," - ",groups.group) as title'), 'schedule.end', 'schedule.start', DB::raw('((fac.faculty = "'.$faculty.'") OR (faculties.faculty = "'.$faculty.'")) as editable'))->join('groups', 'schedule.group_id', 'groups.id')->join('users', 'schedule.user_id', 'users.id')->join('academic_titles', 'users.academic_title_id', 'academic_titles.id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('halls', 'schedule.hall_id', 'halls.id')->join('faculties as fac', 'halls.faculty_id', 'fac.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->join('lush', 'schedule.lush_id', 'lush.id')->where(function ($query) use ($request) {
                $query->where('schedule.hall_id', $request->hall_id);
            })->union($without_group)->where('schedule.to', $semester_end)->get()->toArray();

            $data = array();
            $data['schedule'] = $scheduler;
            $data['limits'] = ['start'=>settings()['start_schedule_time'],'end'=>settings()['end_schedule_time']];

            $isNotSubject = ($request->subject_id == null || $request->subject_id == 0);
            $isNotTeacher = ($request->prof_id == null || $request->prof_id == 0);
            $isNotLush = ($request->lu_id == null || $request->lu_id == 0);

            if(!($isNotSubject || $isNotTeacher || $isNotLush)){

                $availabilities = Availability::select('id', 'start', 'end', 'allowed')->where('user_id', $request->prof_id)->get()->toArray();

                $max_hours_limit = null;
                $max_hours_per_day = null;

                if (Lush::select('lush')->where('id', $request->lu_id)->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                    $max_hours = CP::select('lecture_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['lecture_hours'];
                } else {
                    $max_hours = CP::select('exercise_hours')->where('id', explode('-', $request->subject_id)[1])->get()->toArray()[0]['exercise_hours'];
                }

                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->prof_id)->where('subject_id', explode('-', $request->subject_id)[0])->exists()) {
                    $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $request->prof_id)->where('subject_id', explode('-', $request->subject_id)[0])->where('to', $semester_end)->get()->toArray()[0]['diff'] / 60;

                    $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
                } else {
                    $max_hours_limit = $max_hours * 45;
                }

                $data['availability'] = $availabilities;
                $data['max_hours'] = $max_hours_limit;
            }

            $sdsds = json_encode($data);
            return response()->json($sdsds, 200);
        } catch (QueryException $e) {
            return;
        } catch (\ErrorException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start' => 'bail|required|date',
                'end' => 'bail|required|date',
                'semester'=>'bail|required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'fails' => true,
                    'title' => 'Gabim gjatë validimit',
                    'msg' => array_values($validator->getMessageBag()->toArray())[0]
                ], 400);
            }

            if (Carbon::parse($request->end)->diffInMinutes(Carbon::parse($request->start)) < 45) {
                return response()->json([
                    'fails' => true,
                    'title' => 'Gabim gjatë validimit',
                    'msg' => 'Ju lutem caktoni kohën më të madhe se 45 minuta',
                ], 400);
            }

            $settings = Setting::all()->toArray()[0];

            $data = Schedule::find($id)->toArray();
            $data['hall_id'] = ($request->hall_id != null) ? (int)$request->hall_id : $data['hall_id'];

            $cps = null;
            $lectureOrExercise_Hours = null;
            $hours_per_day = null;

            if (Lush::find($data['lush_id'])->lush == 'Ligjëratë') {
                $cps = CP::select('id')->where('subject_id', $data['subject_id'])->where('user_id', $data['user_id'])->get()->toArray()[0]['id'];
                $lectureOrExercise_Hours = CP::select('lecture_hours')->where('id', $cps)->get()->toArray()[0]['lecture_hours'];
                $hours_per_day = $settings['max_hour_day_lecture'];
            } else {
                $cps = CP::select('cps.id')->join('ca', 'cps.id', 'ca.cps_id')->join('schedule', 'cps.subject_id', 'schedule.subject_id')->where('schedule.subject_id', $data['subject_id'])->where('ca.user_id', $data['user_id'])->get()->toArray()[0]['id'];
                $lectureOrExercise_Hours = CP::select('exercise_hours')->where('id', $cps)->get()->toArray()[0]['exercise_hours'];
                $hours_per_day = $settings['max_hour_day_exercise'];
            }

            $data['subject_id'] = $data['subject_id'].'-'.$cps;
            //oret per profesorin dhe lenden e caktuar pa pjesen e selektuar
            $current_database_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where(function ($query) use ($request,$id,$data) {
                $query->where('subject_id', explode('-', $data['subject_id'])[0]);
                $query->where('user_id', $data['user_id']);
                $query->where('id', '<>', $id);
            })->get()->toArray()[0]['diff']/2700;


            //oret e selektuara
            $selected_hours = Carbon::parse($request->end)->diffInMinutes(Carbon::parse($request->start))/45;

//            dd($current_database_hours,$selected_hours,$lectureOrExercise_Hours);
            if (!(($selected_hours) <= $lectureOrExercise_Hours)) {
                return response()->json([
                    'fails' => true,
                    'title' => 'Gabim me orën e caktuar!',
                    'msg' => 'Mësimdhënësi i caktuar ka kaluar orët e lejuara për lëndën e caktuar.',
                ], 400);
            }

            $user = User::find($data['user_id']);
            $allowed_hours = 100;
            if (StatusAcademicTitle::select('status_id')->whereAcademicTitleId($user->academic_title_id)->exists()) {
                $allowed_hours = StatusAcademicTitle::select('normal_hours')->whereAcademicTitleId($user->academic_title_id)->get()->toArray()[0]['normal_hours'] + StatusAcademicTitle::select('extra_hours')->whereAcademicTitleId($user->academic_title_id)->get()->toArray()[0]['extra_hours'];
            }

            $users_hours = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where(function ($query) use ($request,$settings,$id,$data) {
                $query->where('user_id', $data['user_id']);
                $query->where('id', '<>', $id);
                $query->where('to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
            })->get()->toArray()[0]['diff']/2700;

            if ($allowed_hours < ($users_hours+$selected_hours)) {
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim me mësimdhënësin!',
                    'msg'=>'Mësimdhënësi ka kaluar orët e lejuara për javë!'
                ], 400);
            }

            $semester = Subject::select('semester')->where('id', explode('-', $data['subject_id'])[0])->get()->toArray()[0]['semester'];

            $inside_semester_boundaries = ($request->semester == "false") ? (Setting::where(function ($query) use ($request) {
                $query->where('start_winter_semester', '<', Carbon::now()->toDateString());
                $query->where('start_winter_semester', '<', Carbon::now()->toDateString());
                $query->where('end_winter_semester', '>', Carbon::now()->toDateString());
                $query->where('end_winter_semester', '>', Carbon::now()->toDateString());
            })->exists()) : (Setting::where(function ($query) use ($request) {
                $query->where('start_summer_semester', '<', Carbon::now()->toDateString());
                $query->where('start_summer_semester', '<', Carbon::now()->toDateString());
                $query->where('end_summer_semester', '>', Carbon::now()->toDateString());
                $query->where('end_summer_semester', '>', Carbon::now()->toDateString());
            })->exists());
            if (!Schedule::select('user_id')->where(function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                });
                $query->orWhere(function ($query) use ($request) {
                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                    $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                });
                $query->orWhere(function ($query) use ($request) {
                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                });
            })->where(function ($query) use ($request,$settings,$id,$data) {
                $query->where('user_id', $data['user_id']);
                $query->where('id', '<>', $id);
                $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                $query->where('to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
            })->exists()) {
                //nese koha qe profesori ka orar eshte me e vogel ose e barabarte me kohen e lejuar per ligjerate/ushtrime
                if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $data['user_id'])->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'))->where('to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester'])->get()->toArray()[0]['diff'] / 2700 <= $lectureOrExercise_Hours) {
                    //Nese data e selektuar eshte brenda domenit te semestrit
                    if ($inside_semester_boundaries) {
                        //kur profesori nuk ka te dhena ne orar per daten e selektuar (start,end)
                        //nese grupi eshte i pergjithshem
                        if ($data['group_id'] == 0) {
                            //nese grupi nuk eshte i nxënë
                            if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                $query->where(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                });
                            })->where(function ($query) use ($semester,$request,$settings) {
                                $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                $query->where('subjects.semester', $semester);
                                $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                $query->where('schedule.to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                $query->where('schedule.id', '<>', $request->id);
                            })->exists()) {
                                if (Schedule::find($id)->update(['to'=>Carbon::now()->toDateString()])) {
                                    $schedule = Schedule::find($id);
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $data['user_id'];
                                    $schedule->hall_id = $data['hall_id'];
                                    $schedule->subject_id = explode('-', $data['subject_id'])[0];
                                    $schedule->lush_id = $data['lush_id'];
                                    $schedule->group_id = $data['group_id'];
                                    $schedule->from = Carbon::now()->toDateString();
                                    $schedule->to = ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];
                                    if ($schedule->save()) {
                                        return response()->json([
                                                'success'=>true
                                            ], 200);
                                    }
                                } else {
                                    return response()->json([
                                            'fails' => true,
                                        ], 400);
                                }
                            } else {
                                //nese grupi eshte i nxene
                                return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                            }
                        } else {
                            //kur grupi nuk eshte i pergjithshem
                            if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                $query->where(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                });
                            })->where(function ($query) use ($data) {
                                $query->where('group_id', $data['group_id']);
                                $query->orWhere('group_id', null);
                            })->where(function ($query) use ($semester,$request,$settings) {
                                $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                $query->where('subjects.semester', $semester);
                                $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                $query->where('schedule.to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                $query->where('schedule.id', '<>', $request->id);
                            })->exists()) {
                                if (Schedule::find($id)->update(['to'=>Carbon::now()->toDateString()])) {
                                    $schedule = Schedule::find($id);
                                    $schedule->start = $request->start;
                                    $schedule->end = $request->end;
                                    $schedule->user_id = $data['user_id'];
                                    $schedule->hall_id = $data['hall_id'];
                                    $schedule->subject_id = explode('-', $data['subject_id'])[0];
                                    $schedule->lush_id = $data['lush_id'];
                                    $schedule->group_id = $data['group_id'];
                                    $schedule->from = Carbon::now()->toDateString();
                                    $schedule->to = ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester'];
                                    if ($schedule->save()) {
                                        return response()->json([
                                                'success'=>true
                                            ], 200);
                                    }
                                } else {
                                    return response()->json([
                                            'fails' => true,
                                        ], 400);
                                }
                            } else {
                                return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                            }
                        }
                    } else {
                        //jasht kufijve te semestrit
                        //nese grupi eshte i pergjithshem
                        if ($request->group_id == 0) {
                            //nese grupi nuk eshte i nxënë
                            if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                $query->where(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                });
                            })->where(function ($query) use ($semester,$request,$settings) {
                                $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                $query->where('subjects.semester', $semester);
                                $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                $query->where('schedule.to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                $query->where('schedule.id', '<>', $request->id);
                            })->exists()) {
                                if (Schedule::find($id)->update(['start'=>$request->start,'end'=>$request->end,'hall_id'=>$data['hall_id']])) {
                                    return response()->json([
                                            'success'=>true
                                        ], 200);
                                } else {
                                    return response()->json([
                                            'fails' => true,
                                        ], 400);
                                }
                            } else {
                                //nese grupi eshte i nxene
                                return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                            }
                        } else {
                            //kur grupi nuk eshte i pergjithshem
                            if (!Schedule::select('group_id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->where(function ($query) use ($request) {
                                $query->where(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(end)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->addMinute()->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->subMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '>', Carbon::parse($request->start)->toTimeString());
                                });
                                $query->orWhere(function ($query) use ($request) {
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->start)->addMinute()->toTimeString());
                                    $query->where(DB::raw('TIME(start)'), '<', Carbon::parse($request->end)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->start)->toTimeString());
                                    $query->where(DB::raw('TIME(end)'), '>', Carbon::parse($request->end)->subMinute()->toTimeString());
                                });
                            })->where(function ($query) use ($data) {
                                $query->where('group_id', $data['group_id']);
                                $query->orWhere('group_id', null);
                            })->where(function ($query) use ($semester,$request,$settings) {
                                $query->where(DB::raw('DAYNAME(start)'), Carbon::parse($request->start)->format('l'));
                                $query->where('subjects.semester', $semester);
                                $query->where('faculties.faculty', explode('_', Sentinel::getUser()->roles()->first()->name)[1]);
                                $query->where('schedule.to', ($request->semester == "false") ? $settings['end_winter_semester'] : $settings['end_summer_semester']);
                                $query->where('schedule.id', '<>', $request->id);
                            })->exists()) {
                                if (Schedule::find($id)->update(['start'=>$request->start,'end'=>$request->end,'hall_id'=>$data['hall_id']])) {
                                    return response()->json([
                                            'success'=>true
                                        ], 200);
                                } else {
                                    return response()->json([
                                            'fails' => true,
                                        ], 400);
                                }
                            } else {
                                return response()->json([
                                        'fails' => true,
                                        'title' => 'Gabim me grupin',
                                        'msg' => 'Grupi është i nxënë në këtë kohë!'
                                    ], 400);
                            }
                        }
                    }
                } else {
                    return response()->json([
                            'fails' => true,
                            'title' => 'Lajmërim',
                            'msg' => 'Profesori i caktuar ka kaluar oret e lejuara per kete dite!'
                        ], 400);
                }
            } else {
                return response()->json([
                        'fails' => true,
                        'title' => 'Lajmërim',
                        'msg' => 'Mësimdhënësi është i nxënë në këtë kohë!'
                    ], 400);
            }
        } catch (QueryException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server!',
                'msg' => $e->getMessage()
            ], 500);
        } catch (ErrorException $e) {
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server!',
                'msg' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $data = Schedule::find($request->id)->toArray();

        $cps = getCps($data['user_id'],$data['subject_id']);
        if (Schedule::find($request->id)->delete()) {
            $max_hours_limit = null;
            if (Lush::select('lush')->where('id', $data['lush_id'])->get()->toArray()[0]['lush'] == 'Ligjëratë') {
                $max_hours = CP::select('lecture_hours')->where('id', $cps)->get()->toArray()[0]['lecture_hours'];
            } else {
                $max_hours = CP::select('exercise_hours')->where('id', $cps)->get()->toArray()[0]['exercise_hours'];
            }

            $semester_end = null;

            DB::enableQueryLog();
            if ($request->semester == "false") {
                $semester_end = Setting::select('end_winter_semester')->get()->toArray()[0]['end_winter_semester'];
            } else {
                $semester_end = Setting::select('end_summer_semester')->get()->toArray()[0]['end_summer_semester'];
            }

            if (Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $data['user_id'])->where('subject_id', $data['subject_id'])->exists()) {
                $minutes_schedule = Schedule::select(DB::raw('SUM(TIME_TO_SEC(TIMEDIFF(end,start))) as diff'))->where('user_id', $data['user_id'])->where('subject_id', $data['subject_id'])->where('to', $semester_end)->get()->toArray()[0]['diff'] / 60;

                $max_hours_limit = ($max_hours * 45) - $minutes_schedule;
            } else {
                $max_hours_limit = $max_hours * 45;
            }
            return response()->json([
                'success' => true,
                'max_hours' => $max_hours_limit
            ], 200);
        }
    }

    public function destroyAllSchedule(Request $request){
        $hasRecords = Schedule::whereHas('subject.department.faculty',function($query){
            $query->whereFaculty(explode('_', user()->roles()->first()->name)[1]);
        })->exists();
        if($hasRecords){
            $isDeleted = Schedule::whereHas('subject.department.faculty',function($query){
                $query->whereFaculty(explode('_', user()->roles()->first()->name)[1]);
            })->delete();

            if($isDeleted){
                return redirect('scheduler');
            }
        }else{
            return redirect('scheduler');
        }
    }
}
