Highcharts.chart('barChart', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Cantidad de socios con retención de dividendos'
    },
    subtitle: {
        text: 'Período: Abril 2020'
    },
    xAxis: {
        categories: ['Enero', 'Febrero', 'Marzo', 'Abril'],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Cantidad de socios',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Retenido',
        data: [238, 197, 131, 68]
    }, {
        name: 'No retenido',
        data: [242, 291, 361, 427]
    }, {
        name: 'Sin información',
        data: [20, 12, 8, 5]
    }]
});
