<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use DB;
use Validator;

class DepartmentController extends Controller
{

    public function index()
    {
        try{
        $department = Department::all();
        return view('Menaxho.Departamentet.panel')->with('department', $department);

        }catch(QueryException $e) {
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin'
            ],500);
        }
    }

    public function create()
    {
        return View::make('menaxho.departmentet.panel');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'department' => 'required|alpha|max:250|min:2',
                'faculty_id' => 'required|numeric|exists:faculties,id'
            ]);

        if ($validator->fails()) {
            return response()->json([
                    'fails'=>'true',
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);

        }else{
            $department = new Department;
            $department->department = $request->department;
            $department->faculty_id = $request->faculty_id;

            if($department->save()){
                return response()->json([
                        'fails'=>'true',
                        'title'=>'sukses',
                        'msg'=>'Te dhenat u regjistruan me sukses'
                    ],200);
            }
        }
    }

    public function show(Request $request)
    {
        try{
            $department = Department::select('departments.id','departments.department','faculties.faculty','faculties.id as faculty_id')->join('faculties','departments.faculty_id','faculties.id')->where('departments.department','like','%'.$request->search.'%')->orWhere('faculties.faculty','like','%'.$request->search.'%')->paginate(10);

            return view('Menaxho.Departamentet.panel',['department'=>$department]);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
            ],500);
        }

    }

    public function edit(Request $request,$id)
    {
        try{
            $validation = Validator::make($request->all(),[
                'department' => 'bail|required|alpha|max:190',
                'faculty_id' => 'bail|required|numeric|min:1|exists:faculties,id'
            ]);

            if($validation->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validation->getMessageBag()->toArray()
                ],400);
            }

            $department = Department::find($id);

            $department->department = $request->department;
            $department->faculty_id = $request->faculty_id;

            if($department->save()){
                return response()->json([
                    'success'=>true,
                    'title'=>'Sukses',
                    'msg' => 'Të dhënat u ndryshuan me sukses!'
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim internal',
                    'msg'=>'Ju lutemi kontaktoni mbështetësit e faqes!'
                ],500);
            }

        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin'
            ],500);
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        $department->delete();

        return redirect('departamentPanel');
    }
}
