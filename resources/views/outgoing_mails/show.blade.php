@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Surat Keluar</h3>
                    <p>Halaman detail surat keluar.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('surat-keluar.index') }}">Surat Keluar</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('surat-keluar.show', $mail->id) }}">Detail</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">

            <div class="row">
                
                @hasrole('admin-surat')
                    @if ($mail->status === 0)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Verifikasi Surat</h4>
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
                                            <form action="{{ route('surat-keluar.verifikasi', $mail->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                                                @csrf
                                                @method('PATCH')
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label>Nomor Surat <i class="text-danger">*</i></label>
                                                            <input type="text" name="letter_number" class="form-control" required placeholder="Masukan Nomor Surat" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-4">
                                                            <label>Tanggal Nomor Surat <i class="text-danger">*</i></label>
                                                            <input type="date" name="date" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary me-1 mb-1">Verifikasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endhasrole

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Detail Surat</h4>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Nomor Surat</b>
                                            @if (!$mail->date_letter_number)
                                                <p>-</p>
                                            @else
                                                <p>{{ $mail->letter_number }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Tanggal Nomor Surat</b>
                                            @if (!$mail->date_letter_number)
                                                <p>-</p>
                                            @else
                                                <p>{{ \Carbon\Carbon::parse($mail->date_letter_number)->translatedFormat('d F Y') }}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Penerima</b>
                                            <p>{{ $mail->receiver }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Divisi Surat</b>
                                            <p>
                                                {{ $mail->dataDivision->name }}
                                            </p>
                                        </div>
                                        <div class="col-md-12 p-2">
                                            <b class="card-title mb-2">Perihal</b>
                                            <p>{{ $mail->regarding }}</p>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Terakhir diupdate
                                        {{ \Carbon\Carbon::parse($mail->updated_at)->calendar() }} <br>
                                        oleh {{ $mail->dataUser->name }}
                                    </small>
                                </div>
                                <div class="form-actions d-flex justify-content-end">
                                    @can('surat-keluar-edit')
                                        <a class="btn btn-primary m-2" href="{{ route('surat-keluar.edit',$mail->id) }}">Edit</a>
                                    @endcan
                                    @can('surat-keluar-delete')
                                        <button class="btn btn-danger m-2" type="button" onclick="deleteData(`{{ route('surat-keluar.destroy', $mail->id) }}`)">
                                            Hapus
                                        </button>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-content">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title">Lampiran</h4>
                                <a href="{{ route('download-file', $mail->attachment_file) }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="bi bi-cloud-download"></i> Unduh
                                </a>
                            </div>
                            <div class="card-body">
                                <iframe src="https://docs.google.com/viewerng/viewer?url={{ $mail->attachment_file_url }}&embedded=true" frameborder="0" height="600px" width="100%">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </section>
    </div>
@endsection

@section('javascript')
@endsection