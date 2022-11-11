<!--
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com/cloud-weather-station-esp32-esp8266/

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.
-->
<?php
include_once('esp-database.php');
if ($_GET["readingsCount"]) {
    $data = $_GET["readingsCount"];
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $readings_count = $_GET["readingsCount"];
} // default readings count set to 20
else {
    $readings_count = 20;
}

$last_reading = getLastReadings();
$last_reading_temp = $last_reading["value1"];
$last_reading_humi = $last_reading["value2"];
$last_reading_time = $last_reading["reading_time"];

// Uncomment to set timezone to - 1 hour (you can change 1 to any number)
//$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time - 1 hours"));
// Uncomment to set timezone to + 7 hours (you can change 7 to any number)
//$last_reading_time = date("Y-m-d H:i:s", strtotime("$last_reading_time + 7 hours"));

$min_temp = minReading($readings_count, 'value1');
$max_temp = maxReading($readings_count, 'value1');
$avg_temp = avgReading($readings_count, 'value1');

$min_humi = minReading($readings_count, 'value2');
$max_humi = maxReading($readings_count, 'value2');
$avg_humi = avgReading($readings_count, 'value2');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>UptcParking</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="esp-style.css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <style>
        #weatherWidget .currentDesc {
            color: #ffffff !important;
        }

        .traffic-chart {
            min-height: 335px;
        }

        #flotPie1 {
            height: 150px;
        }

        #flotPie1 td {
            padding: 3px;
        }

        #flotPie1 table {
            top: 20px !important;
            right: -10px !important;
        }

        .chart-container {
            display: table;
            min-width: 270px;
            text-align: left;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        #flotLine5 {
            height: 105px;
        }

        #flotBarChart {
            height: 150px;
        }

        #cellPaiChart {
            height: 160px;
        }

        

    </style>
</head>
<body>

<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header" >
        <a href="analitys.php?readingsCount=20"><img src="homa.png" alt="Logo"> Home</a>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <a href="analitys.php?readingsCount=20"><img src="datos.png" alt="Logo"> Analitys</a>
            <!-- <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a> -->
        </div>
    </div>
</header>

<section class="content" >
    <!-- Animated -->
    <h1 class="title">
        Welcome to UPTC-Parking <img src="estacionamiento logo.png" alt="Logo"></a>
    </h1>
    
    <div class="animated fadeIn" >
        <!-- Widgets  -->
        <div class="row" >
                <?php

                $result = getAllReadings($readings_count);
                if ($result) {
                    $row = $result->fetch_assoc();
                    $row_value1 = $row["value1"];
                    $row_value2 = $row["value2"];
                    $row_value3 = $row["value3"];
                    $row_value4 = $row["value4"];

                    $parking1 = $row_value1 == 0 ? "libre" : "ocupado";
                    $parking2 = $row_value2 == 0 ? "libre" : "ocupado";
                    $parking3 = $row_value3 == 0 ? "libre" : "ocupado";
                    $parking4 = $row_value4 == 0 ? "libre" : "ocupado";

                    $libre = "background: #58d68d;";
                    $ocupado = "background: #f494a4;";

                    echo '<div class="col-lg-3 col-md-6">
                    <div class="card" style="'. ( $row_value1 == 0 ? $libre : $ocupado ) .'">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib">
                                    <i class="pe-7s-car"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                        <span class="count">
                                        '. ( $row_value1 == 0 ? "Libre" : "Ocupado" ) .'
                                        </span></div>
                                        <div>
                                        Parking 1
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-lg-3 col-md-6">
                    <div class="card" style="'. ( $row_value2 == 0 ? $libre : $ocupado ) .'">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib">
                                    <i class="pe-7s-car"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                        <span class="count">
                                        '. ( $row_value2 == 0 ? "Libre" : "Ocupado" ) .'
                                        </span></div>
                                        <div>
                                        Parking 2
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-lg-3 col-md-6">
                    <div class="card" style="'. ( $row_value3 == 0 ? $libre : $ocupado ) .'">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib">
                                    <i class="pe-7s-car"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                        <span class="count">
                                        '. ( $row_value3 == 0 ? "Libre" : "Ocupado" ) .'
                                        </span></div>
                                        <div>
                                        Parking 3
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card" style="'. ( $row_value4 == 0 ? $libre : $ocupado ) .'">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib">
                                    <i class="pe-7s-car"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                        <span class="count">
                                        '. ( $row_value4 == 0 ? "Libre" : "Ocupado" ) .'
                                        </span></div>
                                        <div>
                                        Parking 4
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                  ';
                    $result->free();
                }
                ?>
            </div>
            <a class="navbar-brand"><img src="Inicio.jpg" alt="Logo"></a>
    </div>
    <!-- .animated -->
   
    <!-- /.section -->
        
</section>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>

<!--  Chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

<!--Chartist Chart-->
<script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
</body>
</html>
