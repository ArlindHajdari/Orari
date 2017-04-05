<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Subject;
use App\Models\DepartmentSubject;
use Validator;
use DB;

class LendetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try
        {

        $lendet = DepartmentSubject::select('subjects.id','subjects.subject','subjects.ects','subjects.semester','subjecttypes.subjecttype','departments.department')->join('departments','department_subjects.department_id','departments.id')->join('subjects','department_subjects.subject_id','subjects.id')->join('subjecttypes','subjects.subjecttype_id','subjecttypes.id')->paginate(10 );

//        $lendet = Subject::paginate(15);

        return view('Menaxho.Lendet.panel',['lendet'=>$lendet]);
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
        try{
            $validator = Validator::make($request->all(),[
                'search' => 'bail|required|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $lendet = DepartmentSubject::select('subjects.id','subjects.subject','subjects.ects','subjects.semester','subjecttypes.subjecttype','departments.department')->join('departments','department_subjects.department_id','departments.id')->join('subjects','department_subjects.subject_id','subjects.id')->join('subjecttypes','subjects.subjecttype_id','subjecttypes.id')->where
                ('subject','like','%'.$request->search.'%')->paginate(1);
            return view('Menaxho.Lendet.panel',['lendet'=>$lendet]);
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

    public function store(Request $request)
    {
       try
       {
           $validation = Validator::make($request->all(),[
               'subject'=>'bail|required|alpha|max:190',
               'ects'=>'bail|required|string|max:12',
               'subjecttype_id'=>'bail|required|int',
               'semester'=>'bail|required|string|max:1',
               'department'=>'bail|required|int'
           ]);

           if($validation->fails())
           {
               return response()->json([
                   'fails'=>true,
                   'errors'=>$validation->getMessageBag()->toArray()
               ],400);
           }

           $subject = new Subject;
           $subject->subject = $request->subject;
           $subject->ects = $request->ects;
           $subject->semester = $request->semester;
           $subject->subjecttype_id = $request->subjecttype_id;
           $subject->save();

           $depSub = new DepartmentSubject;
           $depSub->department_id = $request->department;
           $depSub->subject_id = $subject->id;

           if($depSub->save()){

               return response()->json([
                   'success'=>true,
                   'title'=>'Sukses',
                   'msg'=>'Të dhënat u shtuan me sukses!'],200);
           }
           else{

               return response()->json([
                   'fails'=>true,
                   'title'=>'Gabim gjatë regjistrimit',
                   'msg'=>'Ju lutemi shikoni për parregullsi në të dhëna!'
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'search' => 'bail|required|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);
            }

            $data = User::select('users.id',DB::raw("concat(academic_titles.academic_title,'.',users.first_name,' ',users.last_name) as full_name"),'cpas.cpa','users.email','users.personal_number','users.log_id','users.photo')
                ->join('cpas','users.cpa_id','cpas.id')->join('academic_titles','users.academic_title_id','academic_titles.id')->where
                ('first_name',
                    'like','%'.$request->search.'%')
                ->orWhere
                ('last_name',
                    'like','%'
                    .$request->search.'%')->orWhere('email','like','%'.$request->search.'%')->orWhere('personal_number','like','%'.$request->search.'%')->orWhere('log_id','like','%'.$request->search.'%')->where('cpa','Dekan')->paginate
                (10);
            return view('Menaxho.Dekanet.panel',['data'=>$data]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lendet = Subject::findOrFail($id);
        return redirect()->back()->with($lendet);
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
        //
    }
}
