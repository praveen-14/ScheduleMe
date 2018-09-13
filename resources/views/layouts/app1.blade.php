<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Schedule Me</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/css/skins/_all-skins.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    @yield('css')
</head>

<body class="skin-blue sidebar-mini">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>ScheduleMe</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="http://ww1.prweb.com/prfiles/2013/01/14/10321495/gI_110604_Nimble%20schedule.png"
                                     class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="https://previews.123rf.com/images/martialred/martialred1507/martialred150700669/42613349-User-account-circle-flat-icon-for-apps-and-websites-Stock-Vector.jpg"
                                         class="img-circle" alt="User Image"/>
                                    <p>
                                        {!! Auth::user()->name !!}
                                        <small>Member since {!! Auth::user()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Sign out
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar" id="sidebar-wrapper">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="http://img.clipartall.com/user-20clipart-user-clipart-300_300.png" class="img-circle"
                             alt="User Image"/>
                    </div>
                    <div class="pull-left info">
                        @if (Auth::guest())
                            <p>InfyOm</p>
                        @else
                            <p>{{ Auth::user()->name}}</p>
                    @endif
                    <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- search form (Optional) -->
                <form action="#" method="get" class="sidebar-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search..."/>
                        <span class="input-group-btn">
            <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
                    </div>
                </form>
                <!-- Sidebar Menu -->

                <ul class="sidebar-menu">
                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>
                        <li class="treeview">
                            <a href="{{ url('/developerHome') }}">
                                <i class="fa fa-dashboard"></i> <span>Home</span>
                                <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                            </a>
                        </li>


                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-files-o"></i>--}}
                        {{--<span>Layout Options</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<span class="label label-primary pull-right">4</span>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="../layout/top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>--}}
                        {{--<li><a href="../layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>--}}
                        {{--<li><a href="../layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>--}}
                        {{--<li><a href="../layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="../widgets.html">--}}
                        {{--<i class="fa fa-th"></i> <span>Widgets</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<small class="label pull-right bg-green">new</small>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-pie-chart"></i>--}}
                        {{--<span>Charts</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="../charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>--}}
                        {{--<li><a href="../charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>--}}
                        {{--<li><a href="../charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>--}}
                        {{--<li><a href="../charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="treeview active">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-laptop"></i>--}}
                        {{--<span>UI Elements</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li class="active"><a href="general.html"><i class="fa fa-circle-o"></i> General</a></li>--}}
                        {{--<li><a href="icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>--}}
                        {{--<li><a href="buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>--}}
                        {{--<li><a href="sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>--}}
                        {{--<li><a href="timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>--}}
                        {{--<li><a href="modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-edit"></i> <span>Forms</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="../forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>--}}
                        {{--<li><a href="../forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>--}}
                        {{--<li><a href="../forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-table"></i> <span>Tables</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="../tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>--}}
                        {{--<li><a href="../tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="../calendar.html">--}}
                        {{--<i class="fa fa-calendar"></i> <span>Calendar</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<small class="label pull-right bg-red">3</small>--}}
                        {{--<small class="label pull-right bg-blue">17</small>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="../mailbox/mailbox.html">--}}
                        {{--<i class="fa fa-envelope"></i> <span>Mailbox</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<small class="label pull-right bg-yellow">12</small>--}}
                        {{--<small class="label pull-right bg-green">16</small>--}}
                        {{--<small class="label pull-right bg-red">5</small>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-folder"></i> <span>Examples</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="../examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>--}}
                        {{--<li><a href="../examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>--}}
                        {{--<li><a href="../examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>--}}
                        {{--<li><a href="../examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>--}}
                        {{--<li><a href="../examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>--}}
                        {{--<li><a href="../examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>--}}
                        {{--<li><a href="../examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>--}}
                        {{--<li><a href="../examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>--}}
                        {{--<li><a href="../examples/pace.html"><i class="fa fa-circle-o"></i> Pace Page</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="treeview">--}}
                        {{--<a href="#">--}}
                        {{--<i class="fa fa-share"></i> <span>Multilevel</span>--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>--}}
                        {{--<li>--}}
                        {{--<a href="#"><i class="fa fa-circle-o"></i> Level One--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>--}}
                        {{--<li>--}}
                        {{--<a href="#"><i class="fa fa-circle-o"></i> Level Two--}}
                        {{--<span class="pull-right-container">--}}
                        {{--<i class="fa fa-angle-left pull-right"></i>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                        {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>--}}
                        {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li><a href="../../documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>--}}
                        {{--<li class="header">LABELS</li>--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>--}}
                        {{--<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>--}}
                    </ul>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2016 <a href="#">Company</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    InfyOm Generator
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif

<!-- jQuery 2.1.4 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.3.3/js/app.min.js"></script>

@yield('scripts')
</body>
</html>