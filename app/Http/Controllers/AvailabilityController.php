<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use Validator;
use App\Models\Availability;
use Sentinel;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $availabilities = Availability::select('id','TimeFrom as start','TimeTo as end')->where('user_id',Sentinel::getUser()->id)->get()->toJson();

            //return $availabilities;
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
            $avail = new Availability;
            $avail->TimeFrom = Carbon::parse($request->startTime)->toDateTimeString();
            $avail->TimeTo = Carbon::parse($request->endTime)->toDateTimeString();
            $avail->user_id = Sentinel::getUser()->id;

            if($avail->save()){
                return response()->json([
                    'id'=>$avail->id,
                ],200);
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
<<<<<<< HEAD
    public function edit(Request $request,$id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'start' => 'bail|required|date|after_or_equal:2017-04-17|before_or_equal:2017-04-22',
                'end' => 'bail|required|date|after_or_equal:2017-04-17|before_or_equal:2017-04-22',
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
            $avail->TimeFrom = $start->toDateTimeString();
            $avail->TimeTo = $end->toDateTimeString();
=======
    public function edit(Response $response,$id)
    {
        dd($response->all());
        try{
            $avail = Availability::find($id);
            $avail->TimeFrom = $response->start;
            $avail->TimeTo = $response->end;
>>>>>>> origin/master

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
                'success'=>true
            ],200);
        }
    }
}
