/*global $, document, Chart, LINECHART, data, options, window*/
$(document).ready(function () {

    'use strict';

    // ------------------------------------------------------- //
    // Line Chart
    // ------------------------------------------------------ //
    var legendState = true;
    if ($(window).outerWidth() < 576) {
        legendState = false;
    }

    var LINECHART = $('#lineCahrt');

    var lineChartData = () => {
        return {
            labels: datas.map(data => data['created_at']),
            datasets: [
                {
                    label: "Temperature",
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: '#f15765',
                    pointBorderColor: '#da4c59',
                    pointHoverBackgroundColor: '#da4c59',
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 0,
                    data: datas.map(data => data['temperature']),
                    spanGaps: false,
                    yAxisID: "y-axis-1"
                },
                {
                    label: "Humidity",
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: "#54e69d",
                    pointHoverBackgroundColor: "#44c384",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "#44c384",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: datas.map(data => data['humidity']),
                    spanGaps: false,
                    yAxisID: "y-axis-2"
                },
                {
                    label: "Pump",
                    fill: true,
                    lineTension: 0,
                    backgroundColor: "transparent",
                    borderColor: "#54e69d",
                    pointHoverBackgroundColor: "#44c384",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    borderWidth: 1,
                    pointBorderColor: "#44c384",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBorderColor: "#fff",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: datas.map(data => data['created_at']?100:0),
                    spanGaps: false,
                    yAxisID: "y-axis-1"
                }
            ]
        }
    }

    var lineChart = Chart.Line(LINECHART, {
        data: lineChartData(),
        options: {
            responsive: true,
            hoverMode: 'index',
            stacked: false,
            title:{
                display: true,
                text:'Chart.js Line Chart - Multi Axis'
            },
            scales: {
                yAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                }, {
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "right",
                    id: "y-axis-2",

                    // grid line settings
                    gridLines: {
                        drawOnChartArea: false, // only want the grid lines for one axis to show up
                    },
                }],
            }
        }
    });

    // var myLineChart = new Chart(LINECHART, {
    //     type: 'line',
    //     options: {
    //         scales: {
    //             xAxes: [{
    //                 display: true,
    //                 gridLines: {
    //                     display: false
    //                 }
    //             }],
    //             yAxes: [{
    //                 display: true,
    //                 gridLines: {
    //                     display: false
    //                 }
    //             }]
    //         },
    //         legend: {
    //             display: legendState
    //         }
    //     },
    //     data: {
    //         labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17"],
    //         datasets: [
    //             {
    //                 label: "Temperature",
    //                 fill: false,
    //                 lineTension: 0,
    //                 backgroundColor: "transparent",
    //                 borderColor: '#f15765',
    //                 pointBorderColor: '#da4c59',
    //                 pointHoverBackgroundColor: '#da4c59',
    //                 borderCapStyle: 'butt',
    //                 borderDash: [],
    //                 borderDashOffset: 0.0,
    //                 borderJoinStyle: 'miter',
    //                 borderWidth: 1,
    //                 pointBackgroundColor: "#fff",
    //                 pointBorderWidth: 1,
    //                 pointHoverRadius: 5,
    //                 pointHoverBorderColor: "#fff",
    //                 pointHoverBorderWidth: 2,
    //                 pointRadius: 1,
    //                 pointHitRadius: 0,
    //                 data: [50, 20, 60, 31, 52, 22, 40, 25, 30, 68, 56, 40, 60, 43, 55, 39, 47],
    //                 spanGaps: false,
    //                 yAxisID: "y-axis-1"
    //             },
    //             {
    //                 label: "Humidity",
    //                 fill: false,
    //                 lineTension: 0,
    //                 backgroundColor: "transparent",
    //                 borderColor: "#54e69d",
    //                 pointHoverBackgroundColor: "#44c384",
    //                 borderCapStyle: 'butt',
    //                 borderDash: [],
    //                 borderDashOffset: 0.0,
    //                 borderJoinStyle: 'miter',
    //                 borderWidth: 1,
    //                 pointBorderColor: "#44c384",
    //                 pointBackgroundColor: "#fff",
    //                 pointBorderWidth: 1,
    //                 pointHoverRadius: 5,
    //                 pointHoverBorderColor: "#fff",
    //                 pointHoverBorderWidth: 2,
    //                 pointRadius: 1,
    //                 pointHitRadius: 10,
    //                 data: [20, 7, 35, 17, 26, 8, 18, 10, 14, 46, 30, 30, 14, 28, 17, 25, 17, 40],
    //                 spanGaps: false,
    //                 yAxisID: "y-axis-2"
    //             },
    //             {
    //                 label: "Pump",
    //                 fill: true,
    //                 lineTension: 0,
    //                 backgroundColor: "transparent",
    //                 borderColor: "#54e69d",
    //                 pointHoverBackgroundColor: "#44c384",
    //                 borderCapStyle: 'butt',
    //                 borderDash: [],
    //                 borderDashOffset: 0.0,
    //                 borderJoinStyle: 'miter',
    //                 borderWidth: 1,
    //                 pointBorderColor: "#44c384",
    //                 pointBackgroundColor: "#fff",
    //                 pointBorderWidth: 1,
    //                 pointHoverRadius: 5,
    //                 pointHoverBorderColor: "#fff",
    //                 pointHoverBorderWidth: 2,
    //                 pointRadius: 1,
    //                 pointHitRadius: 10,
    //                 data: [20, 7, 25, 97, 26, 82, 18, 1, 45, 26, 39, 30, 14, 28, 67, 29, 7, 30],
    //                 spanGaps: false,
    //                 yAxisID: "y-axis-1"
    //             }
    //         ]
    //     }
    // });

});
