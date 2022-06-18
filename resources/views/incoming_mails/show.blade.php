@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Surat Masuk</h3>
                    <p>Halaman detail surat masuk.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('surat-masuk.show', $mail->id) }}">Detail</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">

            <div class="row">
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Detail Surat</h4>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Nomor Surat</b>
                                            <p>{{ $mail->referance_number }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Tanggal Nomor Surat</b>
                                            <p>{{ \Carbon\Carbon::parse($mail->date_letter_number)->translatedFormat('d F Y') }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Nomor Asal Surat</b>
                                            <p>{{ $mail->origin_number }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Tanggal Nomor Asal Surat</b>
                                            <p>{{ \Carbon\Carbon::parse($mail->date_of_origin)->translatedFormat('d F Y') }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Pengirim</b>
                                            <p>{{ $mail->sender_mail }}</p>
                                        </div>
                                        <div class="col-md-6 p-2">
                                            <b class="card-title mb-2">Tingkat Kepentingan</b><br>
                                            @if ($mail->is_urgent === 1)
                                                <p class="badge bg-primary">Penting</p>
                                            @else
                                                <p class="badge bg-primary">Umum</p>
                                            @endif
                                        </div>
                                        <div class="col-md-12 p-2">
                                            <b class="card-title mb-2">Divisi Tujuan Surat</b>
                                            <ul>
                                                @foreach ($mail->dataDivision as $mailDivision)
                                                    <li>{{ $mailDivision->division->name }}</li>
                                                @endforeach
                                            </ul>
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