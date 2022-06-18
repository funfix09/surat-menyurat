<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Surat | DISDUK CAPIL KOTA SAMARINDA</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendors/toastify/toastify.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/app.css">
    <link rel="shortcut icon" href="{{ asset('/') }}assets/images/logo/logo_capil.png">
    <style type="text/css">
        .sidebar-wrapper .sidebar-header img {
            height: 3rem;
        }
    </style>
    @yield('css')
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="#"><img src="{{ asset('/') }}assets/images/logo/logo_dashboard.png" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item{{ (Request::segment(1) == null) ? ' active' : null }}">
                            <a href="{{ url('/') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        @hasrole('superadmin')
                        <li class="sidebar-item has-sub{{ (in_array(Request::segment(1), ['divisions', 'roles', 'permissions', 'users'])) ? ' active' : null }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>Master Data</span>
                            </a>
                            <ul class="submenu{{ (in_array(Request::segment(1), ['divisions', 'roles', 'permissions', 'users'])) ? ' active' : null }}">
                                <li class="submenu-item{{ (Request::segment(1) == 'divisions') ? ' active' : null }}">
                                    <a href="{{ route('divisions.index') }}">Divisi</a>
                                </li>
                                @can('role-list')
                                    <li class="submenu-item{{ (Request::segment(1) == 'roles' || Request::segment(1) == 'permissions') ? ' active' : null }}">
                                        <a href="{{ route('roles.index') }}">Roles</a>
                                    </li>
                                @endcan
                                <li class="submenu-item{{ (Request::segment(1) == 'users') ? ' active' : null }}">
                                    <a href="{{ route('users.index') }}">Pengguna</a>
                                </li>
                            </ul>
                        </li>
                        @endhasrole

                        @can('surat-masuk-list')
                            <li class="sidebar-item{{ (Request::segment(1) == 'surat-masuk') ? ' active' : null }}">
                                <a href="{{ route('surat-masuk.index') }}" class='sidebar-link'>
                                    <i class="bi bi-inbox-fill"></i>
                                    <span>Surat Masuk</span>
                                </a>
                            </li>
                        @endcan

                        @can('surat-keluar-list')
                            <li class="sidebar-item{{ (Request::segment(1) == 'surat-keluar') ? ' active' : null }}">
                                <a href="{{ route('surat-keluar.index') }}" class='sidebar-link'>
                                    <i class="bi bi-mailbox2"></i>
                                    <span>Surat Keluar</span>
                                </a>
                            </li>
                        @endcan

                        <li class="sidebar-title">Pengaturan</li>

                        <li class="sidebar-item">
                            <a href="{{ route('account') }}" class='sidebar-link'>
                                <i class="bi bi-person-fill"></i>
                                <span>Akun</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link' onclick="logout()">
                                <i class="bi bi-door-open-fill"></i>
                                <span>Keluar</span>
                            </a>

                            <form action="{{ route('logout') }}" method="post" id="form-logout">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>&copy; PKL UNMUL 2022</p>
                    </div>
                </div>
            </footer>
            <form action="" id="form-delete-data" method="POST">
                @method('DELETE')
                @csrf
            </form>
        </div>
    </div>
    <script src="{{ asset('/') }}assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('/') }}assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="{{ asset('/') }}assets/vendors/toastify/toastify.js"></script>
    <script src="{{ asset('/') }}assets/js/mazer.js"></script>
    <script src="{{ asset('/') }}assets/vendors/jquery/jquery.min.js"></script>
    @yield('javascript')
    <script src="{{ asset('/') }}assets/js/custom.js"></script>
    @if (session()->has('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close:true,
                gravity:"bottom",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast();
        </script>
    @endif
</body>

</html>