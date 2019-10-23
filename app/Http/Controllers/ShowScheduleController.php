<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Cp;
use App\Models\Setting;
use DB;

class ShowScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $faculty = Faculty::pluck('faculty','id')->toArray();

            return view('Menaxho.students')->with('faculty',$faculty);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

    public  function getSemesters(Request $request){
        try{
            if($request->c == '0'){
                $settings = Setting::select('id','start_winter_semester as start','end_winter_semester as end')->orderBy('id','DECS')->limit(1)->get()->toArray()[0];
            }else{
                $settings = Setting::select('id','start_summer_semester as start','end_summer_semester as end')->orderBy('id','DECS')->limit(1)->get()->toArray()[0];
            }

            return response()->json($settings,200);
        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

    public function getDepartmentByFaculty(Request $request)
    {
        try{
            $department = Department::select('department','id')->where('faculty_id',$request->id)->pluck('department','id');

            return response()->json($department,200);
        }catch(QueryException $e){
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

    public function getSemesterByDepartment(Request $request)
    {
        try {
            DB::enableQueryLog();
            if($request->c == '0'){
                $semester = Subject::select('semester')->where('department_id',$request->id)->whereRaw('semester%2=1')->distinct()->pluck('semester');
            }else{
                $semester = Subject::select('semester')->where('department_id',$request->id)->whereRaw('semester%2 = 0')->distinct()->pluck('semester');
            }
//            dd(DB::getQueryLog());

            return response()->json($semester, 200);

        }catch (QueryException $e){

            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msh' =>$e->getMessage()
            ],500);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //code here
    }

    public function show(Request $request)
    {
        try{
            DB::enableQueryLog();

            $cps = CP::select('cps.id')->join('subjects','cps.subject_id','subjects.id')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('departments.faculty_id',$request->faculty)->where('subjects.department_id',$request->department)->where('subjects.semester',$request->semester)->get()->toArray();
//            dd(DB::getQueryLog());

            $prof = Schedule::select('cps.id',DB::raw("CONCAT(academic_titles.academic_title,users.first_name,' ',users.last_name) AS teacher"), 'subjects.subject', DB::raw('SUBSTRING(subjecttypes.subjecttype,1,1) AS subjecttype'), DB::raw('SUBSTRING(lush.lush,1,1)as lush'), 'halls.hall', DB::raw('DAYNAME(schedule.start) AS day_name'), DB::raw("TIME_FORMAT(schedule.start,'%H:%i') as start_time"),DB::raw("TIME_FORMAT(schedule.end,'%H:%i') AS end_time"), DB::raw("IFNULL(`groups`.`group`,'') AS `group`"))->join('halls','schedule.hall_id','halls.id')->join('lush','schedule.lush_id','lush.id')->leftJoin('groups',function($query){
                $query->on('schedule.group_id','groups.id');
            })->join('subjects','schedule.subject_id','subjects.id')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->join('cps','subjects.id','cps.subject_id')->join('ca',function($query){
                $query->on('cps.id','ca.cps_id');
            })->join('subjecttypes','subjects.subjecttype_id','subjecttypes.id')->join('users','schedule.user_id','users.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->where(function($query) use ($request){
                $query->where('departments.faculty_id',$request->faculty);
                $query->where('subjects.department_id',$request->department);
                $query->where('subjects.semester',$request->semester);
                $query->where('schedule.from','<=',Carbon::parse($request->date)->toDateString());
                $query->where('schedule.to','>',Carbon::parse($request->date)->toDateString());
            })->where(function($query){
                $query->whereRaw('schedule.user_id = ca.user_id');
                $query->orWhereRaw('schedule.user_id = cps.user_id');
            })->orderBy('cps.id')->distinct()->get()->toArray();
//            dd(DB::getQueryLog());

            $q1 = CP::select('cps.id',DB::raw("CONCAT(academic_titles.academic_title,users.first_name,' ',users.last_name) AS teacher"),DB::raw('SUBSTRING(lush.lush,1,1) as lush'))->join('ca','cps.id','ca.cps_id')->join('users','ca.user_id','users.id')->join('schedule','users.id','schedule.user_id')->join('lush','schedule.lush_id','lush.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->join('subjects','cps.subject_id','subjects.id')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('departments.faculty_id',$request->faculty)->where('subjects.department_id',$request->department)->where('subjects.semester',$request->semester)->where(function($query) use($request){
                $query->where('schedule.from','<=',Carbon::parse($request->date)->toDateString());
                $query->where('schedule.to','>',Carbon::parse($request->date)->toDateString());
            });

            $res = CP::select('cps.id',DB::raw("CONCAT(academic_titles.academic_title,users.first_name,' ',users.last_name) AS teacher"),DB::raw('SUBSTRING(lush.lush,1,1) as lush'))->join('users','cps.user_id','users.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->join('schedule','users.id','schedule.user_id')->join('lush','schedule.lush_id','lush.id')->join('subjects','cps.subject_id','subjects.id')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('departments.faculty_id',$request->faculty)->where('subjects.department_id',$request->department)->where('subjects.semester',$request->semester)->where(function($query) use($request){
                $query->where('schedule.from','<=',Carbon::parse($request->date)->toDateString());
                $query->where('schedule.to','>',Carbon::parse($request->date)->toDateString());
            })->union($q1)->get()->toArray();

            $data = array();
            $data['cps'] = $cps;
            $data['res'] = $res;
            $data['prof'] = $prof;

            return response()->json($data,200);

        }catch (QueryException $e){
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msg' =>$e->getMessage()
            ],500);
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
