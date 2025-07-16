<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" type="image/png" sizes="16x16" href="public/assets/images/logo-mini.svg">
    <title>Admin Press Admin Template - The Ultimate Bootstrap 4 Admin Template</title>
    <!-- Bootstrap Core CSS -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <link href="{{ asset('/public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{asset('/public/assets/plugins/datatables/css/dataTables.dataTables.css')}}" rel="stylesheet" type="text/css" />
    <!-- morris CSS -->
    <link href="{{ asset('/public/assets/plugins/morrisjs/morris.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- You can change the theme colors from here -->
    <link href="{{ asset('/public/assets/css/colors/blue.css') }}" id="theme" rel="stylesheet">
</head>

<body>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header py-3">
                    <a class="navbar-brand d-flex justify-content-center" href="{{ url('dashboard') }}">
                        <!-- Logo icon -->

                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->

                        <img src="{{ url('public/assets/images/logo.svg') }}" alt="homepage" class="dark-logo" />
                        <img src="{{ url('public/assets/images/logo-mini.svg') }}" alt="homepage" class="dark-mini-logo" />


                    </a>


                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>



                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ url('public/assets/images/users/user1.png') }}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-img"><img src="{{ url('public/assets/images/users/user1.png') }}" alt="user"></div>
                                            <div class="u-text">
                                                <h4>Steave Jobs</h4>
                                                <p class="text-muted">varun@gmail.com</p><a href="pages-profile.html" class="btn btn-rounded btn-primary btn-sm">View Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

       <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector('.sidebartoggler').addEventListener('click', function () {
            // Toggle logos
            const darkLogo = document.querySelector('.dark-logo');
            const miniLogo = document.querySelector('.dark-mini-logo');

            const isDarkLogoVisible = window.getComputedStyle(darkLogo).display !== 'none';

            if (isDarkLogoVisible) {
                darkLogo.style.display = 'none';
                miniLogo.style.display = 'block';
            } else {
                darkLogo.style.display = 'block';
                miniLogo.style.display = 'none';
            }

            // Toggle margin
            const wrapper = document.getElementById('page-wrapper');
            const currentMargin = parseInt(window.getComputedStyle(wrapper).marginLeft, 10);

            if (currentMargin === 240) {
                wrapper.style.marginLeft = '60px';
            } else {
                wrapper.style.marginLeft = '240px';
            }
        });
    });
</script>
