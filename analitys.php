<?php
include_once('esp-database.php');
$readings_count = 0;
if ($_GET["readingsCount"]){
    $data = $_GET["readingsCount"];
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $readings_count = $_GET["readingsCount"];
}
// default readings count set to 20
else {
    $readings_count = 20;
}

$dataPerDay = getDataPerDay();

$value1 = $dataPerDay["valor1"];
$value2 = $dataPerDay["valor2"];
$value3 = $dataPerDay["valor3"];
$value4 = $dataPerDay["valor4"];

$last_reading = getLastReadings();
$last_reading_temp = $last_reading["value1"];
$last_reading_humi = $last_reading["value2"];
$last_reading_time = $last_reading["reading_time"];

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    
</head>
<body>

<header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
        <a href="esp-weather-station.php?readingsCount=20"><img src="homa.png" alt="Logo"> Home</a>
            <!-- <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a> -->
        </div>
    </div>
</header>

<section class="content">
    <!-- Animated -->
    <h1 class="title">
                Welcome to Analitys UPTC-Parking
            </h1>
    <br>
    <br>
    <br>
    <h1>Parking graph vs. Number of times used in the day</h1>
    <br>
    <p align="left">The graph is intended to show the number of times a day that each parking lot is used, the information is sent from the ESP32 microcontroller, which sends the information to a database and through it the information is acquired.</p>

<div  style="min-width: 600px; max-width: 1200px; margin: 0 auto;">
    <canvas id="myChart"></canvas>
</div>

<br>
    <br>
    <br>
    
<?php
echo '<h1> View Latest ' . $readings_count . ' Readings</h1>
<br>
<p align="left">The table shows the information stored in the database, which has the information of each sensor, when the value is "1" the parking lot is occupied, when the value is "0" the parking lot is free, this information is replicated for each sensor sends the information from the microcontroller, the place where the parking lot is and the time it was sent to the database.</p>
<br>

<table cellspacing="5" cellpadding="5" id="tableReadings">
                <tr>
                    <th>ID</th>
                    <th>Sensor</th>
                    <th>Location</th>
                    <th>Value 1</th>
                    <th>Value 2</th>
                    <th>Value 3</th>
                    <th>Value 4</th>
                    <th>Timestamp</th>
                </tr>';

$result = getAllReadings($readings_count);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_sensor = $row["sensor"];
        $row_location = $row["location"];
        $row_value1 = $row["value1"];
        $row_value2 = $row["value2"];
        $row_value3 = $row["value3"];
        $row_value4 = $row["value4"];
        $row_reading_time = $row["reading_time"];
        // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time - 1 hours"));
        // Uncomment to set timezone to + 7 hours (you can change 7 to any number)
        //$row_reading_time = date("Y-m-d H:i:s", strtotime("$row_reading_time + 7 hours"));

        echo '<tr>
                    <td>' . $row_id . '</td>
                    <td>' . $row_sensor . '</td>
                    <td>' . $row_location . '</td>
                    <td>' . $row_value1 . '</td>
                    <td>' . $row_value2 . '</td>
                    <td>' . $row_value3 . '</td>
                    <td>' . $row_value4 . '</td>
                    <td>' . $row_reading_time . '</td>
                  </tr>';
    }
    echo '</table>';
    $result->free();
}
?>
        
</section>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const value1 = <?php echo $value1; ?>;
    const value2 = <?php echo $value2; ?>;
    const value3 = <?php echo $value3; ?>;
    const value4 = <?php echo $value4; ?>;

    const labels = [
        'parking lot 1',
        'parking lot 2',
        'parking lot 3',
        'parking lot 4',
    ];
    
    const days = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
        ]

    const data = {
        labels: labels,
        datasets: [{
            label: days[new Date().getDay()] || '',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [value1, value2, value3, value4],
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {}
    };
</script>
<script>
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>

</body>
</html>