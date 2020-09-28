Highcharts.chart('areaChart', {
    chart: {
        type: 'area'
    },
    title: {
        text: 'Acumulado de los dividendos retenidos y pagados durante el año 2019'
    },
    xAxis: {
        categories: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    },
    yAxis: {
        title: {
            text: 'Dividendos acumulados en millones de CLP'
        },
        labels: {
            formatter: function () {
                return this.value / 1000 + 'k';
            }
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.y:,.0f} millones de CLP</b>'
    },
    plotOptions: {
        area: {
            marker: {
                enabled: false,
                symbol: 'circle',
                radius: 2,
                states: {
                    hover: {
                        enabled: true
                    }
                }
            }
        }
    },
    series: [{
        name: 'Dividendos retenidos',
        data: [
            297126, 271176, 238121, 221356, 191152, 151152, 141351, 135959, 106867, 99211,
            69123, 56812
        ]
    }, {
        name: 'Dividendos pagados',
        data: [102874, 128824, 161879, 178644, 208848, 248848, 258649, 264041, 293133, 300789,
            330877, 343188,
        ]
    }]
});
