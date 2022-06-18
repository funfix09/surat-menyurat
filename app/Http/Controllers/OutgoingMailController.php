<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use DataTables;
use Illuminate\Support\Facades\Auth;

class OutgoingMailController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:surat-keluar-list', ['only' => ['index']]);
        $this->middleware('permission:surat-keluar-create', ['only' => ['create','store']]);
        $this->middleware('permission:surat-keluar-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:surat-keluar-delete', ['only' => ['destroy']]);
    }

    private $public_path = 'uploads/attachment_files';

    public function index(Request $request)
    {
        if($request->ajax()) {
            
            $mails = OutgoingMail::with('dataUser','dataDivision')
                                ->select('id','letter_number','receiver','division_id','status','user_id','created_at')
                                ->orderBy('status', 'ASC');

            if($request->has('order') == false){
                $mails = $mails->orderBy('created_at', 'ASC');
            }

            if(Auth::user()->hasRole('karyawan')){
                $mails = $mails->where('user_id', Auth::id());
            }

            if(Auth::user()->hasRole('admin-bidang')){
                $mails = $mails->where('division_id', Auth::user()->division_id);
            }

            return DataTables::of($mails)
                                ->addIndexColumn()
                                ->filter(function ($query) use ($request) {
                                    if ($request->has('receiver')) {
                                        $query->where('receiver', 'like', "%{$request->get('receiver')}%");
                                    }
                                    if ($request->has('date')) {
                                        $query->where('created_at', 'like', "%{$request->get('date')}%");
                                    }
                                })
                                ->addColumn('letter', function($mail) {
                                    $letter = '';
                                    if(!$mail->letter_number){
                                        $letter .= '-';
                                    }else{
                                        $letter .= $mail->letter_number;
                                    }
                                    return $letter;
                                })
                                ->addColumn('division', function($mail) {
                                    $division = $mail->dataDivision->name;
                                    return $division;
                                })
                                ->addColumn('date', function($mail) {
                                    $date = \Carbon\Carbon::parse($mail->created_at)->translatedFormat('d F Y');
                                    return $date;
                                })
                                ->addColumn('verification', function($mail) {
                                    $verif = '';
                                    if($mail->status === 0){
                                        $verif .= '<span class="badge bg-warning">Belum Diverifikasi</span>';
                                    }else{
                                        $verif .= '<span class="badge bg-success">Terverifikasi</span>';
                                    }
                                    return $verif;
                                })
                                ->addColumn('action', function($mail) {
                                    $button = '';
                                    $button .= '<a class="btn btn-outline-info m-1" href="'. route('surat-keluar.show',$mail->id) .'">Detail</a>';
                                    return $button;
                                })
                                ->escapeColumns(['letter, division, date, verification, action'])
                                ->toJson();
        }

        return view('outgoing_mails.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $divisions = Division::select('id','name')->get();

        if(Auth::user()->hasRole('karyawan|admin-bidang')){
            $divisions = $divisions->where('id', Auth::user()->division_id);
        }

        return view('outgoing_mails.create', compact('divisions'));
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
            'receiver'  => 'required|string',
            'purpose'   => 'required',
            'regarding' => 'required',
            'file'      => 'required|file|max:3240'
        ],[],[
            'receiver'  => 'Penerima Surat',
            'purpose'   => 'Divisi Surat',
            'regarding' => 'Perihal',
            'file'      => 'File Lampiran'
        ]);

        $destinationPath = public_path($this->public_path);

        $attachment = '';
        if($request->hasFile('file')){
            $file       = $request->file('file');
            $filename   = 'Lampiran-SK-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $request->file->move($destinationPath, $filename);
            $attachment = $filename;
        }

        OutgoingMail::create([
            'regarding' => $request->regarding,
            'receiver' => $request->receiver,
            'division_id' => $request->purpose,
            'attachment_file' => $attachment,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mail = OutgoingMail::findOrFail($id);

        $mail->attachment_file_url = (env("APP_ENV") == "local") ? "http://infolab.stanford.edu/pub/papers/google.pdf" : asset('uploads/attachment_files/'. $mail->attachment_file);

        return view('outgoing_mails.show', compact('mail'));
    }

    public function verification(Request $request, $id){
        $this->validate($request, [
            'letter_number' => 'required',
            'date'          => 'required',
        ],[],[
            'letter_number' => 'Nomor Surat',
            'date'          => 'Tangal Nomor Surat',
        ]);

        $mail = OutgoingMail::select('id','letter_number','date_letter_number','status','user_id')
                            ->where('id', $id)                    
                            ->first();
        
        $mail->update([
            'letter_number'      => $request->letter_number,
            'date_letter_number' => $request->date,
            'status'             => 1,
            'user_id'            => Auth::id()
        ]);

        return redirect()->route('surat-keluar.show', $mail->id)->with('success', 'Surat keluar berhasil diverifikasi.');
    }
    
    public function edit($id)
    {
        $mail = OutgoingMail::where('id', $id)->first();
        $divisions = Division::select('id','name')->get();

        if (Auth::user()->hasRole('admin-bidang')){
            $divisions = $divisions->where('division_id', Auth::user()->division_id);
        }
        return view('outgoing_mails.edit', compact('mail','divisions'));  
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
            'receiver'  => 'required|string',
            'purpose'   => 'required',
            'regarding' => 'required',
            'file'      => 'nullable|file|max:3240'
        ],[],[
            'receiver'  => 'Penerima Surat',
            'purpose'   => 'Divisi Surat',
            'regarding' => 'Perihal',
            'file'      => 'File Lampiran',
        ]);

        // dd($request->all());

        $mail =  OutgoingMail::where('id', $id)->first();

        $status = 1;
        if($request->has('verification')){
            $status = 0;
        }

        $destinationPath = public_path($this->public_path);

        $attachment = $mail->attachment_file;
        if($request->hasFile('file')){
            $file       = $request->file('file');
            $filename   = 'Lampiran-SK-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $request->file->move($destinationPath, $filename);
            if (is_file( public_path($this->public_path.$attachment)) ){
                unlink( public_path($this->public_path.$attachment) );
            }
            $attachment = $filename;
        }

        $mail->update([
            'regarding'       => $request->regarding,
            'receiver'        => $request->receiver,
            'division_id'     => $request->purpose,
            'attachment_file' => $attachment,
            'status'          => $status,
            'user_id'         => Auth::id()
        ]);
        

        return redirect()->route('surat-keluar.show', $mail->id)->with('success', 'Surat keluar berhasil diedit.');
    }

    public function destroy($id)
    {
        $mail = OutgoingMail::findOrFail($id)->delete();
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dihapus.');
    }
}
