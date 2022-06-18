@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Role</h3>
                    <p>Halaman manajemen role pengguna</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Edit Role</h4>
                        </div>
            
                        <div class="card-body">
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
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group mb-4">
                                            <label>Nama Role</label>
                                            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required placeholder="Masukan nama hak akses">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label>Hak Akses</label>
                                        </div>
                                        @foreach ($permissions as $permission)
                                            <div class="form-check">
                                                <div class="checkbox">
                                                    <input 
                                                        type="checkbox" 
                                                        id="checkbox-{{ $loop->index }}" 
                                                        class="form-check-input" 
                                                        value="{{ $permission->id }}"
                                                        name="permissions[]"{{ (in_array($permission->id, $rolePermissions)) ? ' checked' : null }}>
                                                    <label for="checkbox-{{ $loop->index }}">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
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