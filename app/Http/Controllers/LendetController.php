<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Subject;
use App\Models\SubjectLush;
use App\Models\Faculty;
use App\Models\DepartmentSubject;
use Validator;
use DB;
use Sentinel;

class LendetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function search(Request $request)
    {
        $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

        $lendet = Subject::select('subjects.id','subjects.subject','subjects.ects','subjects.semester','subjects.subjecttype_id', 'subjects.department_id', 'subjecttypes.subjecttype', 'departments.department')->
        join('departments','subjects.department_id', 'departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        join('subjecttypes','subjects.subjecttype_id','subjecttypes.id')->
        where(function($query) use($request){
            $query->orWhere('subjects.subject','like','%'.$request->search.'%');
            $query->orWhere('departments.department','like','%'.$request->search.'%');
            $query->orWhere('subjecttypes.subjecttype','like','%'.$request->search.'%');
        })->where('faculties.faculty',$faculty)->paginate(10);

        return view('Menaxho.Lendet.panel',['lendet'=>$lendet]);
    }

    public function store(Request $request)
    {
        $isLush = array();
       try{
           $validation = Validator::make($request->except('subject_lush'),[
               'subject'=>'bail|required|string|max:190',
               'ects'=>'bail|required|numeric|max:12',
               'semester'=>'bail|required|numeric|min:1',
               'subjecttype_id'=>'bail|required|numeric|min:1',
               'department_id'=>'bail|required|numeric|min:1'
           ]);

           if(empty($request->subject_lush)){
               return response()->json([
                   'fails'=>true,
                   'title'=>'Gabim',
                   'msg'=>'Ju lutem caktoni te pakten nje lush'
               ],500);
           }

           if($validation->fails()){
               return response()->json([
                   'fails'=>true,
                   'errors'=>$validation->getMessageBag()->toArray()
               ],400);
           }

           $vitet = Faculty::select('academic_years')->where('faculty',explode('_',Sentinel::getUser()->roles()->first()->name)[1])->get()->toArray()[0]['academic_years'];
            if($request->semester > ($vitet*2)){
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë regjistrimit',
                    'msg'=>'Semestri nuk mund të jetë më i madhë se '.($vitet*2).'!'
                ],500);
            }

           $dS = Subject::select('subjects.subject','departments.id')->join('departments','subjects.department_id','departments.id')->where([['departments.id',$request->department_id],['subjects.subject',$request->subject]])->exists();

           if($dS){
               return response()->json([
                   'fails'=>true,
                   'title'=>'Gabim gjatë regjistrimit',
                   'msg'=>'Kjo lende eshte regjistruar nje here!'
               ],500);
           } else{
               $subject = new Subject;
               $subject->subject = $request->subject;
               $subject->ects = $request->ects;
               $subject->semester = $request->semester;
               $subject->subjecttype_id = $request->subjecttype_id;
               $subject->department_id = $request->department_id;

               if($subject->save()){
                   foreach($request->subject_lush as $lush){
                       $subLush = new SubjectLush;
                       $subLush->subject_id = $subject->id;
                       $subLush->lush_id = $lush;

                       $isLush[] = $subLush->save();
                   }

                   if(array_true($isLush)){
                       return response()->json([
                           'success'=>true,
                           'title'=>'Sukses',
                           'msg'=>'Të dhënat u shtuan me sukses!'],200);
                   }else{
                       return response()->json([
                           'fails'=>true,
                           'title'=>'Gabim',
                           'msg'=>'Nuk munden te insertohen te gjithat lush per lenden!'
                       ]);
                   }
               }
               else{

                   return response()->json([
                       'fails'=>true,
                       'title'=>'Gabim gjatë regjistrimit',
                       'msg'=>'Ju lutemi shikoni për parregullsi në të dhëna!'
                   ],500);
               }
           }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //code here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
//        DB::enablegetQueryLog;

        try{
        $validation = Validator::make($request->all(),[
            'subject'=>'bail|required|string|max:190',
            'ects'=>'bail|required|numeric|max:12',
            'semester'=>'bail|required|numeric|min:1',
            'subjecttype_id'=>'bail|required|numeric|min:1',
            'department_id'=>'bail|required|numeric|min:1'
        ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            $vitet = Faculty::select('academic_years')->where('faculty',explode('_',Sentinel::getUser()->roles()->first()->name)[1])->get()->toArray()[0]['academic_years'];
            if($request->semester > ($vitet*2)){
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë regjistrimit',
                    'msg'=>'Semestri nuk mund të jetë më i madhë se '.($vitet*2).'!'
                ],500);
            }

            $data = $request->all();

            $subject = Subject::find($id);
            $subject->subject = $data['subject'];
            $subject->ects = $data['ects'];
            $subject->semester = $data['semester'];
            $subject->subjecttype_id = $data['subjecttype_id'];
            $subject->department_id = $data['department_id'];

            if($sub = $subject->save()){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg' => 'Të dhënat u ndryshuan me sukses!'
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim',
                    'msg' => 'Të dhënat nuk u ndryshuan!'
                ],500);
            }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Subject::find($id)->delete()){
            return redirect('LendetPanel');
        }
    }
}
