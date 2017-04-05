<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HallsController extends Controller
{
    
    public function index()
    {
        return view('menaxho.sallat.panel');
    }
    
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
