function barChartConstructor(containerID, xAxisCategories, yAxisTitle, title_Text, subTitle_Text, barData, barType, is_colorByPoint = false) {
    //Grafico de barras genérico
	$(containerID).chart = Highcharts.chart({
        chart: {
            type: barType,
            renderTo: containerID
        },
        title: {
            text: title_Text
        },
        subtitle: {
            text: subTitle_Text
        },
        xAxis: {
            categories: xAxisCategories,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: yAxisTitle,
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
                },
            series: {
                    colorByPoint: is_colorByPoint
                },
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            floating: true,
            borderWidth: 1,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: barData
    });
}
