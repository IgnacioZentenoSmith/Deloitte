// Build the chart
Highcharts.chart('pieChart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Porcentaje de socios con retención de dividendos'
    },
    subtitle: {
        text: 'Período: Abril 2020'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            },
            showInLegend: true
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Porcentaje',
        colorByPoint: true,
        data: [{
            name: 'Retenido',
            y: 43.78,
            sliced: true,
            selected: true
        }, {
            name: 'No retenido',
            y: 43.08
        }, {
            name: 'Sin información',
            y: 13.14
        }]
    }]
});
