function areaChartConstructor(containerID, title_Text, xAxisCategories, yAxisTitle, areaData) {
    //Grafico de torta genérico
	$(containerID).chart = Highcharts.chart({
        chart: {
            type: 'area',
            renderTo: containerID
        },
        title: {
            text: title_Text
        },
        xAxis: {
            categories: xAxisCategories,
        },
        yAxis: {
            title: {
                text: yAxisTitle
            },
            labels: {
                formatter: function () {
                    return this.value / 1000 + 'k';
                }
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y:,.0f}</b>'
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
}
