@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Hak Akses</h3>
                    <p>Halaman tambah hak akses pengguna</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Hak Akses</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Hak Akses</h4>
                        </div>
            
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('permissions.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-4">
                                            <label>Nama Hak Akses</label>
                                            <input type="text" name="name" class="form-control" required placeholder="Masukan nama hak akses">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Aksi</label>
                                        </div>
                                        <ul class="list-unstyled mb-4">
                                            @foreach (['list', 'create', 'edit', 'delete'] as $action)
                                            <li class="d-inline-block me-2 mb-1">
                                                <div class="form-check">
                                                    <div class="checkbox">
                                                        <input 
                                                            type="checkbox" 
                                                            id="checkbox-{{ $loop->index }}" 
                                                            class="form-check-input" 
                                                            value="{{ $action }}"
                                                            name="actions[]">
                                                        <label for="checkbox-{{ $loop->index }}">{{ ucfirst($action) }}</label>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <div class="form-group mt-3">
                                            <button class="btn btn-primary" type="submit">Submit</button>
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