@extends('layout.master')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Manajemen Role</h3>
                    <p>Halaman manajemen role pengguna</p>
                </div>
            </div>
        </div>
        <section class="section">
            <a href="{{ route('permissions.index') }}" class="btn btn-primary mb-4">
                <i class="bi bi-gear-wide-connected"></i> Manajemen Hak Akses
            </a>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <th width="5%">No.</th>
                            <th>Nama Role</th>
                            <th width="25%">Aksi</th>
                        </thead>
                        <tbody class="">
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
                                        @if ($role->name != "superadmin")
                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $roles->render() !!}
                </div>
            </div>
        </section>
    </div>
@endsection