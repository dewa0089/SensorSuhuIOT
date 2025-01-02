<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>


    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        canvas {
            cursor: grab;
        }

        canvas:active {
            cursor: grabbing;
        }

        .chart-container {
    width: 100%;  
    overflow-x: auto;  
    overflow-y: hidden;  
    padding: 0 10px; 
    box-sizing: border-box;  
}

.chart-area {
    width: 2000px; 
    height: 600px;  
    margin: 0 auto;  
    margin-bottom: 20px;  
}

    </style>

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
                    <div><i class="fas fa-home mb-2" style="font-size: 30px;"></i></div>
                    <div><h1 class= "ml-2" style="bold"> DASHBOARD</h1></div>
                  
                        <ul class="navbar-nav ml-auto">
                            
                           
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                    <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                                </a>
                              
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="{{ url('profile') }}">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
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
                  
                  
                    <div class="container-fluid">

                       
                        <div class="mb-3">
                            <h1 id="welcome-message"></h1>
                
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var welcomeMessage = document.getElementById('welcome-message');
                                    var hour = new Date().getHours();
                
                                    var greeting = '';
                
                                    if (hour >= 5 && hour < 12) {
                                        greeting = 'Selamat Pagi';
                                    } else if (hour >= 12 && hour < 17) {
                                        greeting = 'Selamat Siang';
                                    } else if (hour >= 17 && hour < 20) {
                                        greeting = 'Selamat Sore';
                                    } else {
                                        greeting = 'Selamat Malam';
                                    }
                
                                    welcomeMessage.textContent = greeting + ', {{ Auth::user()->name }}';
                                });
                            </script>
                        </div>

                        <div class="container">
                            <div class="row">
                                
                                <div class="col-xl-12 col-lg-7 mx-auto">
                                    <div class="card shadow mb-4">
                                  
                                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                            <h6 class="m-0 font-weight-bold text-primary">History Pengecekan Suhu</h6>
                                        </div>
                                       
                                        <div class="chart-container">
                                            <div class="chart-area">
                                                <canvas id="temperatureHistory"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Sensor Air Website 2024</span>
            </div>
        </div>
    </footer>


   
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

   
    <script src="js/sb-admin-2.min.js"></script>

 
    <script src="vendor/chart.js/Chart.min.js"></script>

    
    <script>
        const historyData = @json($historyData);

       
        console.log('History Data:', historyData);

      
        historyData.sort((a, b) => new Date(a.time) - new Date(b.time));

      
        const sensor1Data = historyData.filter(entry => entry.sensor === 'Sensor 1');
        const sensor2Data = historyData.filter(entry => entry.sensor === 'Sensor 2');

      
        const uniqueLabels = [...new Set(historyData.map(entry => entry.time))];

       
        const uniqueHistoryData = uniqueLabels.map(label => {
            return {
                time: label,
                sensor1Temp: sensor1Data.find(entry => entry.time === label)?.temperature || null,
                sensor2Temp: sensor2Data.find(entry => entry.time === label)?.temperature || null
            };
        });

    
        const ctx = document.getElementById('temperatureHistory').getContext('2d');
const temperatureHistoryChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: uniqueLabels,
        datasets: [
            {
                label: 'Sensor 1',
                data: uniqueHistoryData.map(entry => ({ x: entry.time, y: entry.sensor1Temp })),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true,
            },
            {
                label: 'Sensor 2',
                data: uniqueHistoryData.map(entry => ({ x: entry.time, y: entry.sensor2Temp })),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,  
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'hour',
                    tooltipFormat: 'YYYY-MM-DD HH:mm:ss',
                    displayFormats: { hour: 'MMM D, HH:mm' },
                },
                title: { display: true, text: 'Waktu' },
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Suhu (°C)' },
                ticks: { callback: value => `${value}°C` },
            },
        },
    },
});

    </script>
</body>
</html>
