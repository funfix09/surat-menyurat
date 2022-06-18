@extends('layout.master')

@section('css')
<link rel="stylesheet" href="{{ asset('/') }}assets/vendors/choices.js/choices.min.css" />
@endsection

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Surat Masuk</h3>
                    <p>Halaman manajemen surat masuk.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('surat-masuk.create') }}">Tambah</a></li>
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
                            <h4 class="card-title">Tambah Surat</h4>
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
                                    <form action="{{ route('surat-masuk.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Nomor Surat <i class="text-danger">*</i></label>
                                                    <input type="text" name="letter_number" class="form-control" required placeholder="Masukan Nomor Surat" value="{{ old('letter_number') }}" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Tanggal Nomor Surat <i class="text-danger">*</i></label>
                                                    <input type="date" name="date_letter_number" class="form-control" value="{{ old('date_letter_number') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Nomor Asal Surat <i class="text-danger">*</i></label>
                                                    <input type="text" name="origin_number" class="form-control" required placeholder="Masukan Nomor Asal Surat" value="{{ old('origin_number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Tanggal Nomor Asal Surat <i class="text-danger">*</i></label>
                                                    <input type="date" name="date_origin_number" class="form-control" required value="{{ old('date_origin_number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Pengirim <i class="text-danger">*</i></label>
                                                    <input type="text" name="sender" class="form-control" required placeholder="Masukan Pengirim Surat" value="{{ old('sender') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4">
                                                    <label>Tingkat Kepentingan Surat <i class="text-danger">*</i></label>
                                                    <select name="is_urgent" class="form-select" required>
                                                        <option selected disabled>Pilih Tingkat Kepentingan Surat</option>
                                                        <option value="0" {{ old('is_urgent') == "0" ? 'selected' : ''  }}>Umum</option>
                                                        <option value="1" {{ old('is_urgent') == "1" ? 'selected' : ''  }}>Penting</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Tujuan Divisi Surat <i class="text-danger">*</i></label>
                                                    <select name="purpose[]" class="choices form-select multiple-remove" multiple="multiple" required>
                                                        @foreach ($divisions as $division)
                                                            <option value="{{ $division->id }}">
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
                                                    id="floatingTextarea" rows="4" required>{{ old('regarding') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-4 fs-6">
                                                    <label>File Lampiran <i class="text-danger">*</i></label>
                                                    <input type="file" name="file" class="form-control" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf,application/vnd.ms-excel,.csv,.ppt, .pptx,.xlsx,.xls" required>
                                                    <small>Maksimal ukuran file 5MB | Format: pdf,doc,docx,ppt,pptx,xls,csv </small>
                                                </div>
                                            </div>
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