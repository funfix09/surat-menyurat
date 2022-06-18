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
                            <li class="breadcrumb-item"><a href="#">Edit</a></li>
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
                            <h4 class="card-title">Edit Pengguna</h4>
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
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-group mb-4">
                                            <label>Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required placeholder="Masukan nama pengguna">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label>Alamat Email</label>
                                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required placeholder="Masukan email">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label>Ubah Password (opsional)</label>
                                            <input type="password" name="password" class="form-control" placeholder="Masukan password">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label>Role</label>
                                            <select name="role" id="role-id" class="form-control" required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role }}"{{ ($role == $user_role) ? ' selected' : null }}>{{ ucwords(str_replace('-', ' ', $role)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-4{{ ($user->division_id == null) ? ' d-none' : null }}" id="form-division">
                                            <label>Divisi</label>
                                            <select name="division_id" class="form-control" required>
                                                <option value="0" disabled>-</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}"{{ ($division->id == $user->division_id) ? ' selected' : null }}>{{ $division->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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

@section('javascript')
    <script type="text/javascript">
        document.getElementById('role-id').addEventListener('change', function() {
            if (this.value == 'admin-bidang' || this.value == 'karyawan') {
                document.getElementById('form-division').classList.remove('d-none');
            } else {
                document.getElementById('form-division').classList.add('d-none');
            }
        });
    </script>
@endsection