@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Divisi</h3>
                    <p>Halaman manajemen divisi/bidang.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Divisi</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <a href="{{ route('divisions.create') }}" class="btn btn-primary mb-4">
                <i class="bi bi-plus"></i> Tambah Divisi
            </a>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Nama Divisi</th>
                            <th width="25%">Aksi</th>
                        </thead>
                        <tbody class="">
                            @forelse ($divisions as $division)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $division->name }}</td>
                                    <td>
                                        @can('division-edit')
                                            <a class="btn btn-primary" href="{{ route('divisions.edit',$division->id) }}">Edit</a>
                                        @endcan
                                        @can('division-delete')
                                            <button class="btn btn-danger" type="button" onclick="deleteData(`{{ route('divisions.destroy', $division->id) }}`)">
                                                Hapus
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection