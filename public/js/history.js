moment.locale('nl');

function initializeChartSingle(canvasId, unit, label, data) {
    var dataset = [{
        label: label,
        borderColor: 'rgba(99, 107, 111, 1)',
        fill: false,
        data: data
    }];
    initializeChart(canvasId, unit, dataset);
}

function initializeChartHighLow(canvasId, unit, label, dataHigh, dataLow) {
    var dataset = [{
        label: label + ', minimumtemperaturen',
        borderColor: 'rgba(0, 100, 210, 1)',
        fill: false,
        data: dataLow
    },{
        label: label + ', minimumtemperaturen',
        backgroundColor: 'rgba(99, 107, 111, .3)',
        borderColor: 'rgba(210, 50, 0, 1)',
        fill: '-1',
        data: dataHigh
    }];
    initializeChart(canvasId, unit, dataset);
}

function initializeChart(canvasId, unit, dataset) {
    var canvas = document.getElementById(canvasId).getContext('2d');
    var chart = new Chart(canvas, {
        type: 'line',
        data: {
            datasets: dataset
        },
        options: {
            elements: {
                point: {
                    radius: 0
                }
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        console.log(tooltipItem)
                        return tooltipItem.yLabel;
                    }
                }
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: unit,
                        displayFormats: {
                            hour: 'LT',
                            week: 'D MMM'
                        }
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: false
                    }
                }]
            }
        }
    });

    return chart;
}
