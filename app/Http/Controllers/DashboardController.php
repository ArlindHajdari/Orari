<?php

namespace App\Http\Controllers;

use App\Models\Cpa;
use App\Models\Availability;
use App\Models\Role;
use Illuminate\Http\Request;
use Sentinel;
use Charts;
use Illuminate\Support\Facades\DB;
use App\Models\Hall;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Cp;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::join('cpas','users.cpa_id','cpas.id')->where('cpas.cpa','<>','Dekan');

        $mesimdhenesi = Sentinel::getUser()->id;

        $disponueshmeria = Availability::where('user_id',$mesimdhenesi);

        $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

        $chart = Charts::database(User::all()->where('cpa_id','<>',4), 'bar', 'chartjs')
            ->elementLabel("Total mësimdhënës")
            ->title(' ')
            ->dimensions(1060, 500)
            ->responsive(false)
            ->lastByDay(14, false);

        $chart2 = Charts::database(User::all(), 'bar', 'chartjs')
            ->elementLabel("Total përdorues")
            ->title(' ')
            ->dimensions(1060, 500)
            ->responsive(false)
            ->lastByDay(14, false);

//        $chart3 = Charts::database(User::all(), 'bar', 'highcharts')
//        ->elementLabel("Total")
//        ->dimensions(1310, 500)
//        ->responsive(false)
//        ->groupBy('cpa_id', null, [1 => 'Asistent', 2 => 'Dekan',3=>'Ligjërues' ,4 => 'Profesorët']);

        try {

            $cps = Cp::with(['subject'=>function($query){
                $query->orWhere('subject');
            }])->orWhereHas('user',function($query){
                $query->orWhere('users.first_name');
                $query->orWhere('users.last_name');
            })->with(['user'=>function($query){
                $query->orWhere('first_name');
                $query->orWhere('last_name');
            }])->whereHas('subject.department.faculty', function  ($query) use ($faculty){
                $query->where('faculty',$faculty);
            });

            $halls = Hall::all();

            $halls2 = Hall::select('halls.id','halls.hall')->join('faculties','halls.faculty_id','faculties.id')->where('faculties.faculty',$faculty);

            $users = User::join('cpas','users.cpa_id','cpas.id')->where('cpas.cpa','Dekan');

            $users2 = User::all();

            $faculties = Faculty::all();

            $departments2 = Department::join('faculties','departments.faculty_id','faculties.id')->where('faculties.faculty',$faculty);

            $departments = Department::all();

            $subjects = Subject::all();

            $subjects2 = Subject::select('subjects.id','subjects.subject')->join('departments','subjects.department_id','departments.id')->join('faculties','departments.faculty_id','faculties.id')->where('faculties.faculty',$faculty);

            return view('index')
                ->with('cps', $cps)
                ->with('halls', $halls)
                ->with('halls2', $halls2)
                ->with('users',$users)
                ->with('faculties',$faculties)
                ->with('departments',$departments)
                ->with('departments2',$departments2)
                ->with('subjects',$subjects)
                ->with('subjects2',$subjects2)
                ->with('chart',$chart)
                ->with('chart2',$chart2)
                ->with('disponueshmeria',$disponueshmeria)
                ->with('users2',$users2);

        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin'
            ],500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

}
