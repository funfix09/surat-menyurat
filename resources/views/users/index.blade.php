@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Pengguna</h3>
                    <p>Halaman manajemen pengguna aplikasi</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Pengguna</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="d-flex justify-content-between">
                <a href="{{ route('users.create') }}" class="btn btn-primary mb-4">
                    <i class="bi bi-plus"></i> Tambah Pengguna
                </a>
                <a 
                    class="btn btn-success mb-4" 
                    data-bs-toggle="collapse" 
                    href="#form-search" 
                    role="button" 
                    aria-expanded="{{ (count(request()->except('page')) > 0) ? 'true' : 'false' }}" 
                    aria-controls="form-search">
                    <i class="bi bi-search"></i> Pencarian
                  </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="collapse{{ (count(request()->except('page')) > 0) ? ' show' : null }}" id="form-search">
                        <h5 class="mb-4">Form Pencarian</h5>
                        <form action="{{ route('users.index') }}" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Nama Pengguna</label>
                                        <input type="text" name="name" value="{{ request()->get('name') }}" class="form-control" placeholder="Masukan nama pengguna">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label>Alamat Email</label>
                                        <input type="email" name="email" value="{{ request()->get('email') }}" class="form-control" placeholder="Masukan alamat email">
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label>Divisi</label>
                                        <select name="division_id" class="form-control">
                                            <option selected disabled>Pilih asal divisi</option>
                                            @forelse ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ (request()->get('division_id') == $division->id) ? ' selected' : null }}>{{ $division->name }}</option>
                                            @empty
                                                <option>Belum ada data divisi</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group d-inline">
                                        <button type="submit" class="btn btn-primary mt-4 mx-1">
                                            Submit
                                        </button>
                                        <a href="{{ route('users.index') }}" class="btn btn-danger mt-4 mx-1">
                                            Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr class="mt-0 mb-4">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-lg">
                            <thead>
                                <th width="5%">No.</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="20%">Aksi</th>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            {{ ucwords(str_replace('-', ' ', $user->getRoleNames()->first())) }}
                                            @if ($user->division)
                                                <hr class="my-2"/> {{ $user->division->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$user->hasRole('superadmin'))
                                                @can('user-edit')
                                                    <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                                                @endcan
                                                @can('user-delete')
                                                    <button class="btn btn-danger" type="button" onclick="deleteData(`{{ route('users.destroy', $user->id) }}`)">
                                                        Hapus
                                                    </button>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0">
                    {!! $users->links() !!}
                </div>
            </div>
        </section>
    </div>
@endsection