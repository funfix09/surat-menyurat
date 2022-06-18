@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Akun Pengguna</h3>
                    <p class="text-subtitle text-muted">Halaman profile</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Ubah Akun</h4>
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
                            <form action="{{ route('account.update') }}" method="POST">
                                @csrf
                                <div class="form-group mb-4">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Alamat Email</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email }}" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label>Ubah Password</label>
                                    <input type="password" name="password" placeholder="Isi bila ingin mengubah password..." class="form-control">
                                </div>
                                <div class="form-group mb-4">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi perubahan password ..." class="form-control">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection