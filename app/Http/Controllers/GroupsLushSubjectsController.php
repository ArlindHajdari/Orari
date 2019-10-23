<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\GroupsLushSubject;
use App\Models\SubjectLush;
use App\Models\Group;

class GroupsLushSubjectsController extends Controller
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

    public function getLushFromSubject(Request $request){
        $lush = SubjectLush::select('lush.lush','subject_lush.lush_id')->
        join('lush','subject_lush.lush_id','lush.id')->where('subject_lush.subject_id',$request->subject_id)->
        pluck('lush.lush','subject_lush.lush_id')->toArray();

        return response()->json($lush,200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isGroup = array();

        $messages = [
            'group_id.required' => 'Grupi është i nevojshëm!',
            'lush_id.required' => 'L/USH është i nevojshëm!',
            'lush_id.numeric'=>'L/USH duhet të jetë numër!',
            'lush_id.exists'=>'L/USH i caktuar nuk gjendet në databazë!',
            'subject_id.required' => 'Lënda është i nevojshëm!',
            'subject_id.numeric'=>'Lënda duhet të jetë numër!',
            'subject_id.exists'=>'Lënda e caktuar nuk gjendet në databazë!'
        ];

        $validation = Validator::make($request->all(),[
            'group_id'=>'required|array',
            'lush_id'=>'required|numeric|exists:lush,id',
            'subject_id'=>'required|numeric|exists:subjects,id'
        ],$messages);

        if($validation->fails()){
            return response()->json([
                'fails'=>true,
                'errors'=>$validation->getMessageBag()->toArray()
            ],400);
        }

        $subject_lush_id = SubjectLush::whereLushId($request->lush_id)->whereSubjectId($request->subject_id)->first()->id;

        if($subject_lush_id != null){
            $groupExists = GroupsLushSubject::where(function($query) use($request){
                foreach($request->group_id as $group){
                    $query->orWhere('group_id',$group);
                }
            })->where('subject_lush_id',$subject_lush_id)->exists();
            if(!$groupExists){
                foreach($request->group_id as $group){
                    $groupLushSubject = new GroupsLushSubject;
                    $groupLushSubject->subject_lush_id = $subject_lush_id;
                    $groupLushSubject->group_id = $group;

                    $isGroup[] = $groupLushSubject->save();
                }

                if(array_true($isGroup)){
                    return response()->json([
                        'success'=>true,
                        'title'=>'Sukses',
                        'msg'=>'Të dhënat u shtuan me sukses!'
                    ],200);
                }else{
                    return response()->json([
                        'fails'=>true,
                        'title'=>'Gabim',
                        'msg'=>'Të dhënat nuk u shtuan!'
                    ],500);
                }
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim',
                    'msg'=>'Të dhënat ekzistojnë!'
                ],500);
            }
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Numri identifikues i lëndës dhe l/ush nuk u gjet!'
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
        $messages = [
            'search.alpha_num' => 'Simbolet nuk janë të lejuara!',
            'search.min'=> 'Fjala për kërkim duhet të ketë së paku :min karaktere!'
        ];

        $validation = Validator::make($request->all(),[
            'search'=>'alpha_num|min:4'
        ],$messages);

        if($validation->fails()){
            return response()->json([
                'fails'=>true,
                'errors'=>$validation->errors()->first('search')
            ],400);
        }

        $data = Group::select('groups_lushsubjects.subject_lush_id','groups_lushsubjects.group_id','lush.lush','subjects.subject','groups.group')->
        join('groups_lushsubjects','groups.id','groups_lushsubjects.group_id')->
        join('subject_lush','groups_lushsubjects.subject_lush_id','subject_lush.id')->
        join('lush','subject_lush.lush_id','lush.id')->
        join('subjects','subject_lush.subject_id','subjects.id')->
        join('departments','subjects.department_id','departments.id')->
        join('faculties','departments.faculty_id','faculties.id')->
        where(function($query) use($request){
            $query->orWhere('groups.group','like','%'.$request->search.'%');
            $query->orWhere('lush.lush','like','%'.$request->search.'%');
            $query->orWhere('subjects.subject','like','%'.$request->search.'%');
        })->where('faculties.faculty',explode('_',user()->roles()->first()->name)[1])->orderBy('subjects.id','ASC')->orderBy('lush.id','ASC')->orderBy('groups_lushsubjects.group_id','ASC')->paginate(10);

        return view('Menaxho.Grupet.groupSubjectLush')->with('data',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $group_id,$subject_lush_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($group_id,$subject_lush_id)
    {
        if(GroupsLushSubject::whereGroupId($group_id)->whereSubjectLushId($subject_lush_id)->delete()){
            return redirect('groups-lush-subjects-panel');
        }else{
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim',
                'msg'=>'Të dhënat nuk arritën të fshihen!'
            ],500);
        }
    }
}
