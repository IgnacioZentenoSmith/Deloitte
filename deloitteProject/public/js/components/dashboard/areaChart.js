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
        credits: {
            enabled: false
        },
        series: areaData
    });
}
