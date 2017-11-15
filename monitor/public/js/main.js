
$(document).ready(function() {
    'use strict';

    $("div.loading").fadeOut('slow');

    var legendState = true;
    if ($(window).outerWidth() < 576) {
        legendState = false;
    }

    var LINECHART = $("#lineChart")
    var datas = [];
    var myLineChart = 0;

    var jsonData = $.ajax({
        url: 'http://localhost:8000/log',
        dataType: 'json',
    }).done(function (results) {
        datas = results
        myLineChart = new Chart(LINECHART, {
            type: 'line',
            data: data(),
            options: options
        });
    });

    

    var count = 60;

    var options = {
        responsive: true,
        hoverMode: 'index',
        stacked: false,
        title:{
            display: true,
            text:'Realtime Chart'
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


    let data = function() {
        return {
            labels : datas.map(d => d['created_at']) || [],
            datasets : [
                {
                    label: "Temperature",
                    borderColor: chartColors.red,
                    backgroundColor: chartColors.redA,
                    fill: false,
                    data : datas.map(d => d['temperature']) || [],
                    yAxisID: "y-axis-1"
                },
                {
                    label: "Humidity",
                    borderColor: chartColors.blue,
                    backgroundColor: chartColors.blueA,
                    fill: false,
                    data : datas.map(d => d['humidity']) || [],
                    yAxisID: "y-axis-2"
                },
                {
                    label: "Pump",
                    borderColor: chartColors.green,
                    backgroundColor: chartColors.greenA,
                    fill: true,
                    data : datas.map(d => d['pump']?100:0) || [],
                    yAxisID: "y-axis-1"
                }
            ]
        }
    }
   
    function addData(chart, label, data) {
        chart.data.labels.push(label);
        if (chart.data.labels.length > count)
            chart.data.labels.shift()
        chart.data.datasets.forEach((dataset) => {
            console.log(dataset.label.toLowerCase() + " " + data[dataset.label.toLowerCase()])
            var newData = data[dataset.label.toLowerCase()]
            if (dataset.label.toLowerCase() == 'pump')
                newData = newData ? 100 : 0

            dataset.data.push(newData);
            if (dataset.data.length > count)
                dataset.data.shift()
        });
        chart.update();
    }

    var humidity = 500
    var pump = false
    // setInterval(function () {
    //         let ctime = moment().format("YYYY-MM-DD HH:mm:ss")
    //         let temperature = Math.floor((Math.random() * 17) + 1) + 20
    //         let change = Math.floor((Math.random() * 15) + 1)
    //         humidity += pump ? Math.floor((Math.random() * 40) + 1) + 10 : -Math.floor((Math.random() * 15) + 1)

    //         if (humidity < 300)
    //             pump = true
    //         if (humidity > 500)
    //             pump = false

    //         console.log(ctime + " " + temperature + " " + humidity + " " + pump)
    //         let newData = {
    //             'temperature': temperature,
    //             'humidity': humidity,
    //             'pump': pump ? 100 : 0
    //         }
    //         addData(myLineChart, ctime, newData)
    //     }, 1000
    // );
    
    var pump_status = 0
    var broker_progress = $("#broker_progress");
    var temp_progress = $("#temp_progress");
    var humidity_progress = $("#humidity_progress");
    var pump_progress = $("#pump_progress");
    
    var broker_value = $("#broker_value");
    var temp_value = $("#temp_value");
    var humidity_value = $("#humidity_value");
    var pump_value = $("#pump_value");
    var pump_button = $("#pump_button");

    var noti_list = $("#noti_list");

    // Boker info
    var hostname = "broker.mqtt-cpe.ml";
    var port = 9001;
    var clientid = "cpe24-demo-"+parseInt(Math.random() * 100000, 16);

    var VALUE_TOPIC = "backyard_iot/value";
    var NOTI_TOPIC = "backyard_iot/notification";

    var PUMP_TOPIC = "backyard_iot/pump";


    var client = new Messaging.Client(hostname, port, clientid);
 
    var options = {

        //connection attempt timeout in seconds
        timeout: 3,

        //Gets Called if the connection has successfully been established
        onSuccess: function () {
            console.log("Connected");
            broker_value.text("OK");

            // Subscibe TOPIC
            client.subscribe(VALUE_TOPIC, {qos: 2});
            client.subscribe(NOTI_TOPIC, {qos: 2});
            client.subscribe(PUMP_TOPIC, {qos: 2});
        },

        //Gets Called if the connection could not be established
        onFailure: function (message) {
            console.log("Connection failed: " + message.errorMessage);
            broker_value.text("ERROR");
        },

    };
     
    //Attempt to connect
    client.connect(options);

    client.onMessageArrived = function (message) {
        var topic = message.destinationName;
        var payload = message.payloadString;

        console.log('Topic: ' + topic + '  | ' + payload);
        if (topic == VALUE_TOPIC) {
            let ctime = moment().format("YYYY-MM-DD HH:mm:ss")
            let newData = JSON.parse(payload)
            addData(myLineChart, ctime, newData)

            let temperature = newData['temperature']
            let humidity = newData['humidity']

            temp_progress.css('width', temperature + '%')
            temp_value.text('' + temperature)

            humidity_progress.css('width', humidity*100/1024 + '%')
            humidity_value.text('' + humidity)
        }else if (topic == NOTI_TOPIC) {
            let ctime = moment().format("YYYY-MM-DD HH:mm:ss")
            let newData = JSON.parse(payload)
            noti_list.prepend(
                '<div class="item d-flex align-items-center">' +
                '<div class="text"><a href="#">' +
                    '<h3 class="h5">'+ newData['message'] +'</h3></a><small>Posted on ' + ctime + '.   </small></div>' +
                '</div>'
            )
        }else if (topic == PUMP_TOPIC) {
            pump_status = parseInt(payload)
            if (pump_status == 0) {
                pump_progress.css('width', 0 + '%')
                pump_value.text('Off')
            }else if (pump_status == 1) {
                pump_progress.css('width', 100 + '%')
                pump_value.text('On')
            }else if (pump_status == 2) {
                pump_progress.css('width', 50 + '%')
                pump_value.text('Auto')
            }
        }
    }

    pump_button.click(function () {
        pump_status++;
        pump_status = pump_status > 2 ? 0 : pump_status
        publish("" + pump_status, PUMP_TOPIC, 2, true)
    })

    var publish = function (payload, topic, qos=2, retained=false) {
        var message = new Messaging.Message(payload);
        message.destinationName = topic;
        message.qos = qos;
        message.retained = retained;
        client.send(message);
    }
});