<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use Illuminate\Http\Request;
use App\Models\IncomingMailDivision;

class DivisonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $divisions = Division::orderBy('name', 'asc')->get();
        return view('division.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('division.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        Division::create(['name' => $request->name]);

        return redirect()->route('divisions.index')->with('success', 'Divisi baru berhasil ditambahkan.');
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
        $division = Division::findOrFail($id);
        return view('division.edit', compact('division'));
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
        $this->validate($request, [
            'name' => 'required|string'
        ]);

        Division::where('id', $id)->update(['name' => $request->name]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Division::where('id', $id)->delete();

        IncomingMailDivision::where('division_id', $id)->delete();
        OutgoingMail::where('division_id', $id)->delete();
        User::where('division_id', $id)->update(['division_id' => NULL]);

        return redirect()->route('divisions.index')->with('success', 'Divisi berhasil dihapus.');
        
    }
}
