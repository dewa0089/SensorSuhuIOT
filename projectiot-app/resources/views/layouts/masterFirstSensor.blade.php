<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sensor 1</title>

   
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <header id="header" class="header">
  
        <div id="wrapper">

            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

         
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-water"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">Water Temperature Sensor</div>
                </a>

          
                <hr class="sidebar-divider my-0">

                
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span></a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('firstsensor.index') }}">
                        <i class="fa fa-tint" aria-hidden="true"></i>
                        <span>Sensor 1</span>
                    </a>
                </li>

              
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('secondsensor.index') }}">
                        <i class="fa fa-tint"></i>
                        <span>Sensor 2</span>
                    </a>
                </li>

                


            </ul>
           

            <div id="content-wrapper" class="d-flex flex-column">

              
                <div id="content">

                   
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div><i class="fas fa-tint mb-2" style="font-size: 30px;"></i></div>
                    <div><h1 class= "ml-2">SENSOR 1</h1></div>
                   
                        <ul class="navbar-nav ml-auto">

                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span
                                        class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                    <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                                </a>
                               
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item"
                                        href="{{ url('profile') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                        </ul>

                    </nav>
                

                    <section class="content">

                        @yield('content')

                    </section>
               

                </div>
              

            </div>
        </div>
    </header>



    
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Sensor Suhu Air Website 2024</span>
            </div>
        </div>
    </footer>
    

    </div>
   

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

  
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    
    <script src="js/sb-admin-2.min.js"></script>

    
    <script src="vendor/chart.js/Chart.min.js"></script>

  
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
