<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use DB;
use App\Models\Setting;

class HallsScheduleController extends Controller
{

    public function index()
    {
        try{
            $halls = Hall::pluck('hall','id')->toArray();

            return view('Menaxho.OrariSallave.panel')->with('halls',$halls);

        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        try {
            if($request->semester == '0'){
                $date = Setting::select('end_winter_semester')->get()->toArray()[0]['end_winter_semester'];
            }else{
                $date = Setting::select('end_summer_semester')->get()->toArray()[0]['end_summer_semester'];
            }

            DB::enableQueryLog();
            $data = Schedule::select('schedule.id', DB::raw("CONCAT(academic_titles.academic_title,users.first_name,' ',users.last_name) AS teacher"), 'subjects.subject', 'halls.hall', DB::raw('DAYNAME(schedule.start) AS day_time'), DB::raw("TIME_FORMAT(schedule.start,'%H:%i') as start_time"), DB::raw("TIME_FORMAT(schedule.end,'%H:%i') AS end_time"))->join('halls', 'schedule.hall_id', 'halls.id')->join('subjects', 'schedule.subject_id', 'subjects.id')->join('departments', 'subjects.department_id', 'departments.id')->join('faculties', 'departments.faculty_id', 'faculties.id')->join('users', 'schedule.user_id', 'users.id')->join('academic_titles', 'users.academic_title_id', 'academic_titles.id')->where('schedule.hall_id', $request->hall_id)->where('schedule.to', $date)->get();

//            dd(DB::getQueryLog());

            return response()->json($data,200);
        }catch (QueryException $e){
            return response()->json([
                'fails' => true,
                'title' => 'Gabim ne server',
                'msh' =>$e->getMessage()
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
