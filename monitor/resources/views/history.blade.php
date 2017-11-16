<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Backyard IoT</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/datetimepicker/css/bootstrap-datetimepicker.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="/css/fontastic.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="/css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <style type="text/css">
        /*
        *  Usage:
        *
            <div class="sk-wandering-cubes">
                <div class="sk-cube sk-cube1"></div>
                <div class="sk-cube sk-cube2"></div>
            </div>
        *
        */
        .sk-wandering-cubes {
        top: 45%;
        margin: 40px auto;
        width: 40px;
        height: 40px;
        position: relative; }
        .sk-wandering-cubes .sk-cube {
            background-color: #fff;
            width: 10px;
            height: 10px;
            position: absolute;
            top: 0;
            left: 0;
            -webkit-animation: sk-wanderingCube 1.8s ease-in-out -1.8s infinite both;
                    animation: sk-wanderingCube 1.8s ease-in-out -1.8s infinite both; }
        .sk-wandering-cubes .sk-cube2 {
            -webkit-animation-delay: -0.9s;
                    animation-delay: -0.9s; }
        @-webkit-keyframes sk-wanderingCube {
        0% {
            -webkit-transform: rotate(0deg);
                    transform: rotate(0deg); }
        25% {
            -webkit-transform: translateX(30px) rotate(-90deg) scale(0.5);
                    transform: translateX(30px) rotate(-90deg) scale(0.5); }
        50% {
            /* Hack to make FF rotate in the right direction */
            -webkit-transform: translateX(30px) translateY(30px) rotate(-179deg);
                    transform: translateX(30px) translateY(30px) rotate(-179deg); }
        50.1% {
            -webkit-transform: translateX(30px) translateY(30px) rotate(-180deg);
                    transform: translateX(30px) translateY(30px) rotate(-180deg); }
        75% {
            -webkit-transform: translateX(0) translateY(30px) rotate(-270deg) scale(0.5);
                    transform: translateX(0) translateY(30px) rotate(-270deg) scale(0.5); }
        100% {
            -webkit-transform: rotate(-360deg);
                    transform: rotate(-360deg); } }
        @keyframes sk-wanderingCube {
        0% {
            -webkit-transform: rotate(0deg);
                    transform: rotate(0deg); }
        25% {
            -webkit-transform: translateX(30px) rotate(-90deg) scale(0.5);
                    transform: translateX(30px) rotate(-90deg) scale(0.5); }
        50% {
            /* Hack to make FF rotate in the right direction */
            -webkit-transform: translateX(30px) translateY(30px) rotate(-179deg);
                    transform: translateX(30px) translateY(30px) rotate(-179deg); }
        50.1% {
            -webkit-transform: translateX(30px) translateY(30px) rotate(-180deg);
                    transform: translateX(30px) translateY(30px) rotate(-180deg); }
        75% {
            -webkit-transform: translateX(0) translateY(30px) rotate(-270deg) scale(0.5);
                    transform: translateX(0) translateY(30px) rotate(-270deg) scale(0.5); }
        100% {
            -webkit-transform: rotate(-360deg);
                    transform: rotate(-360deg); } }
      .loading {
        background: #444;
        position: fixed;
        z-index: 999;
        overflow: show;
        margin: auto;
        left:0;
        right:0;
        top:0;
        bottom:0;
      }
    </style>
  </head>
  <body>
    <div class="loading">
      <div class="sk-wandering-cubes">
        <div class="sk-cube sk-cube1"></div>
        <div class="sk-cube sk-cube2"></div>
      </div>
    </div>
    <div class="page home-page">
      <!-- Main Navbar-->
      <header class="header">
        <nav class="navbar">
          <!-- Search Box-->
          <div class="search-box">
            <button class="dismiss"><i class="icon-close"></i></button>
            <form id="searchForm" action="#" role="search">
              <input type="search" placeholder="What are you looking for..." class="form-control">
            </form>
          </div>
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <!-- Navbar Header-->
              <div class="navbar-header">
                <!-- Navbar Brand --><a href="index.html" class="navbar-brand">
                  <div class="brand-text brand-big"><span>Backyard </span><strong>IoT</strong></div>
                  <div class="brand-text brand-small"><strong>BI</strong></div></a>
                <!-- Toggle Button--><a id="toggle-btn" href="#" class="menu-btn active"><span></span><span></span><span></span></a>
              </div>
            </div>
          </div>
        </nav>
      </header>
      <div class="page-content d-flex align-items-stretch" style="min-height: 50em;">
        <!-- Side Navbar -->
        <nav class="side-navbar">
          <!-- Sidebar Header-->
          <div class="sidebar-header d-flex align-items-center">
          </div>
          <!-- Sidebar Navidation Menus-->
          <span class="heading">Main</span>
          <ul class="list-unstyled">
            <li> <a href="/"><i class="fa fa-bar-chart"></i>Dashboard</a></li>
            <li class="active"> <a href="/history"> <i class="icon-grid"></i>History </a></li>
          </ul>
        </nav>
        <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">History {{ $from or "" }} {{ $to or "" }}</h2>
            </div>
          </header>

          <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
                <!-- Line Chart -->
                <!-- Inline Form-->
                <div class="col-lg-12">                           
                  <div class="card">
                    <div class="card-close">
                      <div class="dropdown">
                        <button type="button" id="closeCard3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                        <div aria-labelledby="closeCard3" class="dropdown-menu dropdown-menu-right has-shadow"><a href="#" class="dropdown-item remove"> <i class="fa fa-times"></i>Close</a><a href="#" class="dropdown-item edit"> <i class="fa fa-gear"></i>Edit</a></div>
                      </div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Select Date</h3>
                    </div>
                    <div class="card-body">
                      <form class="form-inline" action="/history" method="post">
                        {{ csrf_field() }}
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="from" class="form-control" placeholder="01/31/2017 11:45 AM"/>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                        <div class='input-group date' id='datetimepicker2'>
                            <input type='text' name="to" class="form-control" placeholder="01/31/2017 1:10 PM"/>
                            <span class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </span>
                        </div>
                        <div class="form-group">
                          <input type="submit" value="Submit" class="mx-sm-3 btn btn-primary">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
              <div class="row bg-white has-shadow">
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-violet"><i class="fa fa-tint"></i></div>
                    <div class="title"><span>Lowest<br>Humidity</span>
                      <div class="progress">
                        <div role="progressbar" style="width: {{ isset($lowHumidity)?$lowHumidity*100/1024:0 }}%; height: 4px;" aria-valuenow="{{ isset($lowHumidity)?$lowHumidity*100/1024:0 }}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-violet"></div>
                      </div>
                    </div>
                    <div class="number"><strong>{{ $lowHumidity or 0 }}</strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-blue"><i class="fa fa-tint"></i></div>
                    <div class="title"><span>Highest<br>Humidity</span>
                      <div class="progress">
                        <div role="progressbar" style="width: {{ isset($maxHumidity)?$maxHumidity*100/1024:0 }}%; height: 4px;" aria-valuenow="{{ isset($maxHumidity)?$maxHumidity*100/1024:0 }}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-blue"></div>
                      </div>
                    </div>
                    <div class="number"><strong>{{ $maxHumidity or 0 }}</strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-orange"><i class="fa fa-thermometer-quarter"></i></div>
                    <div class="title"><span>Lowest<br>Temp.</span>
                      <div class="progress">
                        <div role="progressbar" style="width: {{ $lowTemp or 0 }}%; height: 4px;" aria-valuenow="{{ $lowTemp or 0}}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-orange"></div>
                      </div>
                    </div>
                    <div class="number"><strong>{{ $lowTemp or 0 }}</strong></div>
                  </div>
                </div>
                <!-- Item -->
                <div class="col-xl-3 col-sm-6">
                  <div class="item d-flex align-items-center">
                    <div class="icon bg-red"><i class="fa fa-thermometer-quarter"></i></div>
                    <div class="title"><span>Highest<br>Temp.</span>
                      <div class="progress">
                        <div role="progressbar" style="width: {{ $maxTemp or 0}}%; height: 4px;" aria-valuenow="{{ $maxTemp or 0 }}" aria-valuemin="0" aria-valuemax="100" class="progress-bar bg-red"></div>
                      </div>
                    </div>
                    <div class="number"><strong>{{ $maxTemp or 0}}</strong></div>
                  </div>
                </div>
                <!-- Item -->
              </div>
            </div>
          </section>
          <!-- Dashboard Header Section    -->
          <section class="dashboard-header">
            <div class="container-fluid">
              <div class="row">
                <!-- Line Chart -->
                <div class="chart col-12">
                  <div class="line-chart bg-white d-flex align-items-center justify-content-center has-shadow">
                    <canvas id="lineChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- Feeds Section-->
          <section class="feeds no-padding-top">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="articles card">
                    <div class="card-close">
                      <div class="dropdown">
                        <button type="button" id="closeCard4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                        <div aria-labelledby="closeCard4" class="dropdown-menu dropdown-menu-right has-shadow"><a href="#" class="dropdown-item remove"> <i class="fa fa-times"></i>Close</a><a href="#" class="dropdown-item edit"> <i class="fa fa-gear"></i>Edit</a></div>
                      </div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                      <h2 class="h3">Logs   </h2>
                    </div>
                    <div class="card-body no-padding">
                    @isset($data)
                    @foreach ($data['notifications'] as $notification)
                      <div class="item d-flex align-items-center">
                        <div class="text"><a href="#">
                            <h3 class="h5">{{ $notification->message }}</h3></a><small>Posted on {{ $notification->created_at }}.   </small></div>
                      </div>
                    @endforeach
                    @endisset
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Page Footer-->
          <footer class="main-footer">
            <div class="container-fluid">
              <div class="row">
                <div class="col-sm-6">
                  <p>Backyard IoT &copy; 2017</p>
                </div>
                <div class="col-sm-6 text-right">
                  <p>Design by <a href="https://bootstrapious.com/admin-templates" class="external">Bootstrapious</a></p>
                  <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                </div>
              </div>
            </div>
          </footer>
        </div>
      </div>
    </div>
    <!-- Javascript files-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="/vendor/moment.js/moment.min.js"></script>
    <script src="/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/vendor/chart.js/Chart.min.js"></script>
    <script src="/js/mqttws31.js"></script>
    <script src="/js/front.js"></script>
    <script>
      $(document).ready(function(){
        'use strict';

        $("div.loading").fadeOut('slow');

        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();
        
        let chartColors = {
            red: 'rgb(255, 99, 132)',
            redA: 'rgba(255, 99, 132, 0.1)',
            orange: 'rgb(255, 159, 64)',
            orangeA: 'rgba(255, 159, 64, 0.1)',
            yellow: 'rgb(255, 205, 86)',
            yellowA: 'rgba(255, 205, 86, 0.1)',
            green: 'rgb(75, 192, 192)',
            greenA: 'rgba(75, 192, 192, 0.2)',
            blue: 'rgb(54, 162, 235)',
            blueA: 'rgba(54, 162, 235, 0.1)',
            purple: 'rgb(153, 102, 255)',
            purpleA: 'rgba(153, 102, 255, 0.1)',
            grey: 'rgb(201, 203, 207)',
            greyA: 'rgba(201, 203, 207, 0.1)'
        };

      
        var legendState = true;
        if ($(window).outerWidth() < 576) {
            legendState = false;
        }

      @isset($data)
        var LINECHART = $("#lineChart")
        var datas = [
          @foreach($data['logs'] as $log)
          {
            'temperature': {{ $log->temperature }},
            'humidity': {{ $log->humidity }},
            'pump': {{ $log->pump }},
            'created_at': '{{ $log->created_at }}'
          },
          @endforeach
        ];

        let data = function() {
            return {
                labels : datas.map(d => d['created_at']),
                datasets : [
                    {
                        label: "Temperature",
                        borderColor: chartColors.red,
                        backgroundColor: chartColors.redA,
                        fill: false,
                        data : datas.map(d => d['temperature']),
                        yAxisID: "y-axis-1"
                    },
                    {
                        label: "Humidity",
                        borderColor: chartColors.blue,
                        backgroundColor: chartColors.blueA,
                        fill: false,
                        data : datas.map(d => d['humidity']),
                        yAxisID: "y-axis-2"
                    },
                    {
                        label: "Pump",
                        borderColor: chartColors.green,
                        backgroundColor: chartColors.greenA,
                        fill: true,
                        data : datas.map(d => d['pump']?100:0),
                        yAxisID: "y-axis-1"
                    }
                ]
            }
        }


        var options = {
          responsive: true,
          hoverMode: 'index',
          stacked: false,
          title:{
              display: true,
              text:'History Chart from {{ $from }} to {{ $to }}'
          },
          scales: {
              yAxes: [{
                  label: "Temperature",
                  type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                  display: true,
                  position: "left",
                  id: "y-axis-1",
                  ticks: {
                      min: 100,
                      max: 0
                  }
              }, {
                  label: "Humidity",
                  type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                  display: true,
                  position: "right",
                  id: "y-axis-2",
                  ticks: {
                      min: 1024,
                      max: 0
                  },

                  // grid line settings
                  // gridLines: {
                  //     drawOnChartArea: true, // only want the grid lines for one axis to show up
                  // },
              }],
              xAxes: [{
                  type: "time",
                  time: {
                      unit: "minute",
                      displayFormats: {
                          minute: "YY/MM/DD HH:mm"
                      }
                  }
              }],
          }
        };

        var myLineChart = new Chart(LINECHART, {
              type: 'line',
              data: data(),
              options: options
          });
    @endisset
      });   
    </script>
  </body>
</html>
