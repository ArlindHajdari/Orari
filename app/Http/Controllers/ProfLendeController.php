<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ca;
use DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\QueryException;

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
        try{
            $data = Ca::with(['cp.subject'=>function($query) use ($request){
                $query->where('subject','like','%'.$request->search.'%');
            }])->with(['cp.user'=>function($query) use ($request){
                $query->orWhere('first_name','like','%'.$request->search.'%');
                $query->orWhere('last_name','like','%'.$request->search.'%');
            }])->paginate(15);

//            dd($assistents);
            dd(DB::getQueryLog());
//            dd($data);
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
        //
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
        CP::where('id',$id)->delete();
        return view('Menaxho.Profesor-Lende.panel');
    }
}
