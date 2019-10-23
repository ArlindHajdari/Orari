<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cp;
use App\Models\Ca;
use DB;
use Illuminate\Database\QueryException;
use App\Models\Faculty;
use Validator;
use Sentinel;

class ProfLendeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        DB::enableQueryLog();
        $faculty = explode('_',Sentinel::getUser()->roles()->first()->name)[1];

        try{
            $data = Cp::with(['subject'=>function($query) use ($request){
                $query->orWhere('subject','like','%'.$request->search.'%');
            }])->orWhereHas('user',function($query) use ($request){
                $query->orWhere('users.first_name','like','%'.$request->search.'%');
                $query->orWhere('users.last_name','like','%'.$request->search.'%');
            })->with(['user'=>function($query) use ($request){
                $query->orWhere('first_name','like','%'.$request->search.'%');
                $query->orWhere('last_name','like','%'.$request->search.'%');
            }])->whereHas('subject.department.faculty', function  ($query) use ($faculty){
                $query->where('faculty',$faculty);
            })->paginate(15);

            return view('Menaxho.Profesor-Lende.panel')->with('data',$data);
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title' => 'Gabim ne server',
                'msg' => $e->getMessage()
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
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            foreach ($data as $key => $value) {
                if ($data[$key] == '0') {
                    unset($data[$key]);
                }
            }

            $asis_ids = ['asis_id1', 'asis_id2', 'asis_id3', 'asis_id4', 'asis_id5'];

            $asistentet = array();

            foreach ($asis_ids as $key) {
                if (array_key_exists($key, $data) !== false) {
                    $asistentet[$key] = $data[$key];
                }
            }

            $validate = Validator::make($data, [
                'prof_id' => 'bail|required|numeric|min:1',
                'subject_id' => 'bail|required|numeric|min:1',
                'lecture_hours'=>'bail|required|numeric|min:1',
                'exercise_hours'=>'bail|required|numeric|min:1'
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'fails' => true,
                    'errors' => $validate->getMessageBag()->toArray()
                ], 400);
            }


            if (empty($asistentet)) {
                return response()->json([
                    'fails' => true,
                    'title'=>'Gabim',
                    'msg' => 'Ju lutem zgjedhni të paktën një asistent'
                ], 500);
            }

            if(count($asistentet) > $data['exercise_hours']){
                return response()->json([
                    'fails' => true,
                    'title'=>'Gabim',
                    'msg' => 'Orët për ushtrime nuk mund të jenë më të vogla se numri i asistenteve!'
                ], 500);
            }

            $cp = new Cp;
            $cp->user_id = $data['prof_id'];
            $cp->subject_id = $data['subject_id'];
            $cp->lecture_hours = $data['lecture_hours'];
            $cp->exercise_hours = $data['exercise_hours'];

            $proff_subject_exist = CP::select('subject_id','cps.user_id')->where('subject_id',
                $request->subject_id)->where('user_id',$request->prof_id)->exists();

            $asistents_saved = array();

            $asistents_exist = Ca::select('cps.subject_id','ca.user_id')->join('cps','ca.cps_id', 'cps.id')->join('users','ca.user_id', 'users.id')->join('subjects','cps.subject_id', 'subjects.id')->where('cps.subject_id', $request->subject_id)->where(function ($query) use ($request) {
                $query->where('users.id', $request->asis_id1);
                $query->orWhere('users.id', $request->asis_id2);
                $query->orWhere('users.id', $request->asis_id3);
                $query->orWhere('users.id', $request->asis_id4);
                $query->orWhere('users.id', $request->asis_id5);
            })->exists();

            if($asistents_exist){
                return response()->json([
                    'success' => true,
                    'title' => 'Gabim gjatë futjes së të dhënave!',
                    'msg' => 'Këta asistent janë caktuar një herë për këtë lëndë, ju lutem caktoni asistent të tjerë!'
                ], 500);
            }

            if(!$proff_subject_exist){
                if($cp->save()){
                    foreach($asistentet as $value){
                        $ca = new Ca;
                        $ca->user_id = $value;
                        $ca->cps_id = $cp->id;

                        $asistents_saved[] = $ca->save();
                    }

                    $saved = false;

                    if(count(array_unique($asistents_saved)) === 1){
                        $saved = current($asistents_saved);
                    }

                    if($saved){
                        return response()->json([
                            'success'=>true,
                            'title'=>'Sukses',
                            'msg'=>'Të dhënat u ruajtën me sukses!'
                        ],200);
                    }else{
                        return response()->json([
                            'fails'=> true,
                            'title'=> 'Gabim gjatë futjes së të dhënave',
                            'msg'=>'Të dhënat nuk mundën të futen në databaze, ju lutem kontaktoni mirëmbajtësit e faqes!'
                        ],500);
                    }
                }else{
                    return response()->json([
                        'fails'=> true,
                        'title'=> 'Gabim gjatë futjes së të dhënave',
                        'msg'=>'Të dhënat nuk mundën të futen në databaze, ju lutem kontaktoni mirëmbajtësit e faqes!'
                    ],500);
                }
            }else{
                return response()->json([
                    'fails'=> true,
                    'title'=> 'Gabim gjatë futjes së të dhënave',
                    'msg'=>'Mësimdhënësi është regjistruar një herë për këtë lëndë!'
                ],500);
            }
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Internal server error',
                'msg'=>$e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id, $asis_id1, $asis_id2, $asis_id3, $asis_id4, $asis_id5)
    {
        try{
            $data = $request->all();
            $old_asistents = array();

            //Merr variablat prej funksionit(asistentet) dhe i fut me ni array te ri $old_asistents
            for ($i = 2; $i < 7; $i++) {
                $old_asistents[] = func_get_arg($i);
            }

            $asis_ids = ['asis_id1','asis_id2','asis_id3','asis_id4','asis_id5'];

            $new_asistents = array();

            //inico array me asistentet, duke vendosur edhe key edhe value ne baze te dy array $asis_id dhe $data
            //($request->all())
            foreach($asis_ids as $key){
                if(array_key_exists($key, $data) !== false) {
                    $new_asistents[$key] = $data[$key];
                }
            }

            $validate = Validator::make($data, [
                'prof_id' => 'bail|required|numeric|min:1|exists:users,id',
                'subject_id' => 'bail|required|numeric|min:1|exists:subjects,id',
                'lecture_hours'=>'bail|required|numeric|min:1',
                'exercise_hours'=>'bail|required|numeric|min:1'
            ]);

            if($validate->fails()){
                return response()->json([
                    'fails'=>true,
                    'errors'=>$validate->getMessageBag()->toArray()
                ],400);
            }

            if(empty($new_asistents)){
                return response()->json([
                    'fails'=>true,
                    'errors'=>['asis_id1'=>['Ju lutem zgjedhni të paktën një asistent']]
                ],400);
            }

            $cp = Cp::find($id);
            $cp->user_id = $data['prof_id'];
            $cp->subject_id = $data['subject_id'];
            $cp->lecture_hours = $data['lecture_hours'];
            $cp->exercise_hours = $data['exercise_hours'];


            $assistents_changed = false;

            for($i=0; $i < count($new_asistents); $i++){
                if($old_asistents[$i] != array_values($new_asistents)[$i]){
                    $assistents_changed = true;
                }
            }

            $asistents_saved = array();

            if($cp->save()){
                if($assistents_changed){
                    for($i = 0; $i < 5;$i++){
                        if(array_values($old_asistents)[$i] != 0 && array_values($new_asistents)[$i] != 0){
                            if(array_values($old_asistents)[$i] != array_values($new_asistents)[$i]){
                                $asistents_saved[] =  Ca::where('user_id',array_values($old_asistents)[$i])->where('cps_id',$id)->update(['user_id'=>array_values($new_asistents)[$i],'cps_id'=>$cp->id]);
                            }
                        }
                        elseif(array_values($old_asistents)[$i] == 0 && array_values($new_asistents)[$i] != 0){
                            $ca = new Ca;
                            $ca->user_id = array_values($new_asistents)[$i];
                            $ca->cps_id = $cp->id;

                            $asistents_saved[] = $ca->save();
                        }
                        elseif(array_values($old_asistents)[$i] != 0 && array_values($new_asistents)[$i] == 0){
                            $ca = Ca::where('cps_id',$cp->id)->where('user_id',array_values($old_asistents)[$i]);
                            $asistents_saved[] = $ca->delete();
                        }
                    }

                    $dS = (count(array_unique($asistents_saved)) > 1) ? false : true;

                    if($dS){
                        return response()->json([
                            'fails'=>true,
                            'title'=>'Sukses',
                            'msg' => 'Të dhënat u ndryshuan me sukses!'
                        ],200);
                    }else{
                        return response()->json([
                            'fails'=>true,
                            'title'=>'Gabim',
                            'msg' => 'Asistentet nuk mundën të ndryshohen me sukses!'
                        ],500);
                    }
                }else{
                    return response()->json([
                        'fails'=>true,
                        'title'=>'Sukses',
                        'msg' => 'Të dhënat u ndryshuan me sukses!'
                    ],200);
                }
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim gjatë ruajtjes së profesorit dhe lëndës!',
                    'msg' => 'Ju lutem njoftoni mirëmbajtësit e faqes për gabimin!'
                ],500);
            }
        }
        catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Internal server error',
                'msg'=>$e->getMessage()
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
        CA::where('cps_id',$id)->delete();
        CP::where('id',$id)->delete();
        return redirect('proflende');
    }
}
