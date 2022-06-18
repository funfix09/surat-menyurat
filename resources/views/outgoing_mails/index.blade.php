@extends('layout.master')

@section('css')
<link rel="stylesheet" href="{{ asset('/') }}assets/vendors/jquery-datatables/jquery.dataTables.bootstrap5.min.css">
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
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">

            @can('surat-keluar-create')
                <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary mb-4">
                    <i class="bi bi-plus"></i> Tambah Surat Keluar
                </a>
            @endcan
            
            <div class="collapse" id="collapseExample">
                <div class="card">
                    <div class="card-body">
                        <div class="form">
                            <div class="row">
                                <div class="col-md-6 mb-0">
                                    <div class="form-group">
                                        <label for="receiver">Cari Penerima</label>
                                        <input type="text" class="form-control" id="receiver" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <div class="form-group">
                                        <label for="date">Cari Tangal Surat diinput</label>
                                        <input type="date" class="form-control" id="date" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="button" id="reset-form" class="btn btn-light-secondary me-3 mb-1">Reset</button>
                                    <button type="button" id="search-form"  class="btn btn-primary me-3 mb-1">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped" id="table-data">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Nomor Surat</th>
                            <th>Penerima</th>
                            <th>Divisi</th>
                            <th>Status</th>
                            <th>Tanggal diinput</th>
                            <th width="25%">Aksi</th>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('javascript')
<script src="{{ asset('/') }}assets/vendors/jquery-datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}assets/vendors/jquery-datatables/custom.jquery.dataTables.bootstrap5.min.js"></script>
<script type="text/javascript">
var tableData;
$(function(){

    tableData = $('#table-data').DataTable({
        dom: "<'row align-items-center'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 search-button-collapse'>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        processing: true,
        serverSide: true,
        pageLength: 10,
        ajax: { 
            url: '{{ route('surat-keluar.index') }}',
            data: function(f) {
                f.receiver  = $('#receiver').val();
                f.date = $('#date').val();
            }
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false},
            { data: 'letter', name: 'letter' },
            { data: 'receiver', name: 'receiver' },
            { data: 'division', name: 'division',orderable: false,searchable: false },
            { data: 'verification', name: 'verification',orderable: false,searchable: false },
            { data: 'date', name: 'date' },
            { data: 'action', orderable: false, searchable: false}
        ],
    });

    $('.search-button-collapse').addClass('text-end').html('<a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><i class="bi bi-fw bi-search"></i> Cari</a>')
    $('#search-form').on('click', function(e) {
        e.preventDefault();
        tableData.draw();
    });
    $('#reset-form').on('click', function(e){
        e.preventDefault();
        $('#receiver').val('');
        $('#date').val('');
        tableData.draw();
    });

});
</script>
@endsection