<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use Validator;
use App\Models\Availability;
use Sentinel;
use Carbon\Carbon;
use DateTime;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \DB::enableQueryLog();
        try{
            $availabilities = Availability::select('id','start','end','allowed')->where('user_id', Sentinel::getUser()->id)->get()->toJson();
//            dd(\DB::getQueryLog());
            return view('Menaxho.Disponueshmeria.panel',['json'=>$availabilities]);
        }catch(QueryException $e){
            return;
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
        try{
            $validator = Validator::make($request->all(),[
                'allowed'=>'required|boolean',
                'startTime'=>'required|date',
                'endTime' => 'required|date'
            ]);

            if($validator->fails()){
                return response()->json([
                    false
                ],400);
            }

            $color = ($request->allowed == false) ? '#a30606' : null;

            $avail = new Availability;
            $avail->user_id = Sentinel::getUser()->id;
            $avail->start = Carbon::parse($request->startTime)->toDateTimeString();
            $avail->end = Carbon::parse($request->endTime)->toDateTimeString();
            $avail->allowed = $request->allowed;

            if($avail->save()){
                return response()->json([
                    'success'=>true,
                    'id'=>$avail->id,
                    'color'=> $color,
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim',
                    'msg'=>'Nuk mundën të ruhen të gjitha të dhënat!'
                ],500);
            }
        }
        catch(QueryException $e){
            return;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function store_allday(Request $request){
        try{
            $start_milli = Carbon::parse($request->day)->toDateString().' '.Carbon::createFromTimestamp($request->start_milli / 1000)->toTimeString();
            $end_milli = Carbon::parse($request->day)->toDateString().' '.Carbon::createFromTimestamp($request->end_milli / 1000)->toTimeString();

            $validator = Validator::make($request->all(),[
                'allowed'=>'required|boolean',
                'day'=>'required|date'
            ]);

            if($validator->fails()){
                return response()->json([
                    false
                ],400);
            }

            $avail = new Availability;
            $avail->user_id = Sentinel::getUser()->id;
            $avail->start = Carbon::parse($start_milli)->toDateTimeString();
            $avail->end = Carbon::parse($end_milli)->toDateTimeString();
            $avail->allowed = $request->allowed;

            $color = ($request->allowed == false) ? '#a30606' : null;

            if($avail->save()){
                return response()->json([
                    'success'=>true,
                    'id'=>$avail->id,
                    'start'=>$avail->start,
                    'end'=>$avail->end,
                    'color'=> $color,
                ],200);
            }else{
                return response()->json([
                    'fails'=>true,
                    'title'=>'Gabim',
                    'msg'=>'Nuk mundën të ruhen të gjitha të dhënat!'
                ],500);
            }
        }catch(QueryException $e){
            return;
        }
    }

    public function edit(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'start' => 'bail|required|date',
                'end' => 'bail|required|date',
            ]);

            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);

            $diff = $end->diffInMinutes($start) < 45;

            if($validator->fails() || $diff){
                return response()->json([
                    'errors'=>$validator->getMessageBag()->toArray(),
                    'diffOnMinutesError'
                ],400);
            }

            $avail = Availability::find($id);
            $avail->start = $start->toDateTimeString();
            $avail->end = $end->toDateTimeString();

            if($avail->save()){
                return response()->json([
                    'success'=>true
                ],200);
            }

        }catch(QueryException $e){
            return response()->json([
                'fails'=>true,
                'title'=>'Gabim gjatë azhurnimit!',
                'msg'=>'Të dhënat nuk mund të azhurnohen, ju lutem kontaktoni mirëmbajtësit e faqes!'
            ],400);
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
        if(Availability::find($id)->delete()){
            return response()->json([
                'success'=>true,
                'ID'=>$id
            ],200);
        }
    }
}
