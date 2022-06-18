@extends('layout.master')

@section('css')
<link rel="stylesheet" href="{{ asset('/') }}assets/vendors/choices.js/choices.min.css" />
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Surat Keluar</h3>
                    <p>Halaman manajemen surat keluar.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('surat-keluar.index') }}">Surat Keluar</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('surat-keluar.update', $mail->id) }}">Edit</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Surat</h4>
                        </div>
            
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                            <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form action="{{ route('surat-keluar.update', $mail->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Penerima <i class="text-danger">*</i></label>
                                                    <input type="text" name="receiver" class="form-control" required placeholder="Masukan Penerima Surat" value="{{ $mail->receiver }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Divisi Surat <i class="text-danger">*</i></label>
                                                    <select name="purpose" class="form-select" required>
                                                        <option selected disable>Pilih Divisi</option>
                                                        @foreach ($divisions as $division)
                                                            <option value="{{ $division->id }}" @if($mail->division_id === $division->id) selected @endif>
                                                                {{ $division->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Perihal <i class="text-danger">*</i></label>
                                                    <textarea name="regarding" class="form-control" placeholder="Perihal Surat"
                                                    id="floatingTextarea" rows="4" required>{{ $mail->regarding }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 fs-6">
                                                    <label>File Lampiran <i class="text-danger">*</i></label>
                                                    <input type="file" name="file" class="form-control" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/vnd.ms-excel,.csv,.ppt, .pptx,.xlsx,.xls">
                                                    <small>Upload untuk merubah file</small><br>
                                                    <small>Maksimal ukuran file 5MB | Format: pdf,doc,docx,ppt,pptx,xls,csv </small>
                                                </div>
                                            </div>
                                            @if ($mail->status === 1)
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                class="form-check-input form-check-primary form-check-glow"
                                                                name="verification" id="customColorCheck1">
                                                            <label class="form-check-label" for="customColorCheck1">Perubahan Nomor Surat dan Tanggal</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('javascript')
<script src="{{ asset('/') }}assets/vendors/choices.js/choices.min.js"></script>
<script src="{{ asset('/') }}assets/js/pages/form-element-select.js"></script>
<script type="text/javascript">

$(function(){
    
});

</script>
@endsection