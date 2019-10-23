<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusAcademicTitle;
use DB;
use Validator;
use Illuminate\Database\QueryException;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Menaxho.Statusi.statusi');
    }

    public function create()
    {
        //Code Here
    }

    public function store(Request $request)
    {
        try{
            $rules = array(
                'academic_title' => 'required|numeric|min:1',
                'status' => 'required|numeric|min:1',
                'normal_hours' => 'required|numeric|min:1',
                'extra_hours' => 'required|numeric|min:1',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                        'fails'=>'true',
                        'title'=>'Gabim gjatë regjistrimit',
                        'errors'=>$validator->getMessageBag()->toArray()
                    ],400);
            }

            if(!StatusAcademicTitle::where('academic_title_id',$request->academic_title)->where('status_id',$request->status)->exists()){
                $status = new StatusAcademicTitle;
                $status->academic_title_id = $request->academic_title;
                $status->status_id = $request->status;
                $status->normal_hours = $request->normal_hours;
                $status->extra_hours = $request->extra_hours;

                if($status->save()){
                    return response()->json([
                        'fails'=>'true',
                        'title'=>'Sukses',
                        'msg'=>'Te dhenat u regjistruan me sukses!'
                    ],200);
                }else{
                    return response()->json([
                        'fails' => true,
                        'title' => 'Gabim internal',
                        'msg' => 'Ju lutemi kontaktoni mbështetësit e faqes!'
                    ], 500);
                }
            }else{
                return response()->json([
                    'fails' => true,
                    'title' => 'Gabim internal',
                    'msg' => 'Këto të dhëna janë regjistruar një herë!'
                ], 500);
            }
        }catch (QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage(),
                'msg1' => 'Për arsye të caktuar, nuk mundëm të kontaktojmë me serverin!'
            ],500);
        }
    }

    public function show(Request $request)
    {
        try{
            $status = StatusAcademicTitle::select('academic_titles.academic_title','status.name','status_academic_titles.normal_hours','status_academic_titles.extra_hours','academic_titles.id as academic_title_id','status.id as status')->
            join('academic_titles','status_academic_titles.academic_title_id','academic_titles.id')->join('status','status_academic_titles.status_id','status.id')->
            where('status.name','like','%'.$request->search.'%')->
            orWhere('academic_titles.academic_title','like','%'.$request->search.'%')->paginate(10);

            return view('Menaxho.Statusi.statusi',['status'=>$status]);
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

    public function edit(Request $request,$academic_title_id,$status_id)
    {
        try{
            $rules = array(
                'academic_title' => 'required|numeric|min:1',
                'status' => 'required|numeric|min:1',
                'normal_hours' => 'required|numeric|min:1',
                'extra_hours' => 'required|numeric|min:1',
            );

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'fails'=>'true',
                    'title'=>'Gabim gjatë regjistrimit',
                    'errors'=>$validator->getMessageBag()->toArray()
                ],400);

            }else {

                $status = StatusAcademicTitle::whereAcademicTitleId($academic_title_id)->whereStatusId($status_id)->update([
                    'academic_title_id'=>$request->academic_title,
                    'status_id'=>$request->status,
                    'normal_hours'=>$request->normal_hours,
                    'extra_hours'=>$request->extra_hours
                ]);

                if ($status) {
                    return response()->json([
                        'success' => true,
                        'title' => 'Sukses',
                        'msg' => 'Të dhënat u ndryshuan me sukses!'
                    ], 200);
                }else {
                    return response()->json([
                        'fails' => true,
                        'title' => 'Gabim internal',
                        'msg' => 'Ju lutemi kontaktoni mbështetësit e faqes!'
                    ], 500);
                }
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

    public function destroy($academic_title_id,$status_id)
    {
        $status = StatusAcademicTitle::whereAcademicTitleId($academic_title_id)->whereStatusId($status_id)->delete();

        return redirect('statusPanel');
    }
}
