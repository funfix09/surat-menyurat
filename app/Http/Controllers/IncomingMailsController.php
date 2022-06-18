<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\IncomingMail;
use App\Models\IncomingMailDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use DataTables;
use Illuminate\Support\Facades\Auth;

class IncomingMailsController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:surat-masuk-list', ['only' => ['index']]);
        $this->middleware('permission:surat-masuk-create', ['only' => ['create','store']]);
        $this->middleware('permission:surat-masuk-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:surat-masuk-delete', ['only' => ['destroy']]);
    }

    private $public_path = 'uploads/attachment_files';

    public function index(Request $request)
    {   
        if($request->ajax()) {
            
            $mails = IncomingMail::with('dataDivision.division')->select('id','referance_number','date_letter_number','sender_mail','is_urgent','user_id','created_at');

            if(Auth::user()->hasRole('karyawan')){
                $mails = $mails->where('user_id', Auth::id());
            }

            if(Auth::user()->hasRole('admin-bidang')){
                $mails = $mails->whereHas('dataDivision', function($query){
                    $query->where('division_id', Auth::user()->division_id);
                });
            }

            $mails = $mails->orderBy('is_urgent', 'DESC');

            if($request->has('order') == false){
                $mails = $mails->orderBy('created_at', 'ASC');
            }

            return DataTables::of($mails)
                                ->addIndexColumn()
                                ->filter(function ($query) use ($request) {
                                    if ($request->has('sender')) {
                                        $query->where('sender_mail', 'like', "%{$request->get('sender')}%");
                                    }
                                    if ($request->has('date')) {
                                        $query->where('created_at', 'like', "%{$request->get('date')}%");
                                    }
                                })
                                ->addColumn('urgent', function($mail) {
                                    $urgent = '';
                                    if($mail->is_urgent === 1){
                                        $urgent .= '<span class="badge bg-success">Penting</span>';
                                    }else{
                                        $urgent .= '<span class="badge bg-light-info">Umum</span>';
                                    }
                                    return $urgent;
                                })
                                ->addColumn('division', function($mail) {
                                    $division = '';
                                    $countData = $mail->dataDivision ->count();
                                    foreach($mail->dataDivision as $key => $mailDivision){
                                        if ($mailDivision->division != null) {
                                            $division .= $mailDivision->division->name;
                                            if($key != ($countData-1)){
                                                $division .= ", ";
                                            }
                                        }
                                    }
                                    return $division;
                                })
                                ->addColumn('date', function($mail) {
                                    $date = \Carbon\Carbon::parse($mail->created_at)->translatedFormat('d F Y');
                                    return $date;
                                })
                                ->addColumn('action', function($mail) {
                                    $button = '';
                                    $button .= '<a class="btn btn-outline-info m-1" href="'. route('surat-masuk.show',$mail->id) .'">Detail</a>';
                                    if(Auth::user()->can('surat-masuk-edit')){
                                        $button .= '<a class="btn btn-primary m-1" href="'. route('surat-masuk.edit',$mail->id) .'">Edit</a>';
                                    }
                                    if(Auth::user()->can('surat-masuk-delete')){
                                        $button .= '<button class="btn btn-danger m-1" type="button" onclick="deleteData(`'. route('surat-masuk.destroy', $mail->id) .'`)">Hapus</button>';
                                    }
                                    return $button;
                                })
                                ->escapeColumns(['urgent, division, date, action'])
                                ->toJson();
        }

        return view('incoming_mails.index');
    }

    public function create()
    {
        $divisions = Division::select('id','name')->get();
        return view('incoming_mails.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'letter_number'      => 'required|string',
            'date_letter_number' => 'required|date',
            'origin_number'      => 'required|string',
            'date_origin_number' => 'required|date',
            'sender'             => 'required|string',
            'is_urgent'          => 'required',
            'purpose'            => 'required|array',
            'regarding'          => 'required',
            'file'               => 'required|file|max:3240'
        ],[],[
            'letter_number'      => 'Nomor Surat',
            'date_letter_number' => 'Tanggal Nomor Surat',
            'origin_number'      => 'Nomor Asal Surat',
            'date_origin_number' => 'Tanggal Nomor Asal Surat',
            'sender'             => 'Pengirim',
            'is_urgent'          => 'Tingkat Kepentingan',
            'purpose'            => 'Tujuan Divisi Surat',
            'regarding'          => 'Perihal',
            'file'               => 'File Lampiran'
        ]);

        $destinationPath = public_path($this->public_path);

        $attachment = '';
        if($request->hasFile('file')){
            $file       = $request->file('file');
            $filename   = 'Lampiran-SM-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $request->file->move($destinationPath, $filename);
            $attachment = $filename;
        }

        $mail = IncomingMail::create([
                    'referance_number'   => $request->letter_number,
                    'date_letter_number' => $request->date_letter_number,
                    'origin_number'      => $request->origin_number,
                    'date_of_origin'     => $request->date_origin_number,
                    'sender_mail'        => $request->sender,
                    'regarding'          => $request->regarding,
                    'attachment_file'    => $attachment,
                    'is_urgent'          => $request->is_urgent,
                    'user_id'            => Auth::id()
                ]);

        foreach($request->purpose as $division){
            IncomingMailDivision::create([
                'mail_id'     => $mail->id,
                'division_id' => $division,
            ]);
        }
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show($id)
    {
        $mail = IncomingMail::with(['dataDivision.division','dataUser'])->where('id', $id)->first();

        if (!$mail) {
            abort(404);
        }

        $mail->attachment_file_url = (env("APP_ENV") == "local") ? 
                                    "http://infolab.stanford.edu/pub/papers/google.pdf" : 
                                    asset('uploads/attachment_files/'. $mail->attachment_file);

        return view('incoming_mails.show', compact('mail'));
    }

    public function edit($id)
    {
        $mail      = IncomingMail::with(['dataDivision'])->where('id', $id)->first();
        $divisions = Division    ::select('id','name')->get();
        return view('incoming_mails.edit', compact('mail','divisions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'letter_number'      => 'required|string',
            'date_letter_number' => 'required|date',
            'origin_number'      => 'required|string',
            'date_origin_number' => 'required|date',
            'sender'             => 'required|string',
            'is_urgent'          => 'required',
            'purpose'            => 'required|array',
            'regarding'          => 'required',
            'file'               => 'nullable|file|max:3240'
        ]);

        $mail  = IncomingMail::where('id', $id)->first();

        $destinationPath = public_path($this->public_path);

        $attachment = $mail->attachment_file;
        if($request->hasFile('file')){
            $file       = $request->file('file');
            $filename   = 'Lampiran-SM-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $request->file->move($destinationPath, $filename);
            if (is_file( public_path($this->public_path.$attachment)) ){
                unlink( public_path($this->public_path.$attachment) );
            }
            $attachment = $filename;
        }

        $mail->update([
            'referance_number'   => $request->letter_number,
            'date_letter_number' => $request->date_letter_number,
            'origin_number'      => $request->origin_number,
            'date_of_origin'     => $request->date_origin_number,
            'sender_mail'        => $request->sender,
            'regarding'          => $request->regarding,
            'attachment_file'    => $attachment,
            'is_urgent'          => $request->is_urgent,
            'user_id'            => Auth::id()
        ]);

        $mailDivision = IncomingMailDivision::where('mail_id', $mail->id);
        $mailDivision->delete();

        foreach($request->purpose as $division){
            IncomingMailDivision::create([
                'mail_id'     => $mail->id,
                'division_id' => $division,
            ]);
        }
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diedit.');
    }

    public function destroy($id)
    {
        $mail = IncomingMail::select('id')->where('id', $id)->first()->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Surat Masuk berhasil dihapus.');
    }
}
