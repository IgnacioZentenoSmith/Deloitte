function pieChartConstructor(containerID, title_Text, subTitle_Text, series_Name, pieData) {
    //Grafico de torta genérico
	$(containerID).chart = Highcharts.chart({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            renderTo: containerID
        },
        title: {
            text: title_Text,
        },
        subtitle: {
            text: subTitle_Text,
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
            name: series_Name,
            colorByPoint: true,
            data: pieData.data,
        }]
    });
};
