/**
 * @file
 * Behaviors for the search widget in the admin toolbar.
 */
(function ($) {
  Drupal.behaviors.customChart = {
    attach: function (context, settings) {
      var dataCharts = drupalSettings.mdManager.rh;
      dataCharts.forEach(function (dataChart) {
        Highcharts.chart(dataChart.id, {
          credits: {
            enabled: false
          },
          chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            spacingTop: 0,
            spacingBottom: 0,
            spacingLeft: 0,
            spacingRight: 0
          },
          title: {
            text: dataChart.title,
            fontFamily: 'Cairo',
            align: 'left',
            useHTML: true,
            style: {
              fontSize: "14px",
              fontWeight: '600'
            }
          },
          tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
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
              //innerSize: '38%',
              dataLabels: {
                enabled: false
              },
              showInLegend: true
            }
          },
          series: [{
            name: '',
            colorByPoint: true,
            data: dataChart.dataChart
          }],
          exporting: {
            enabled: false
          }
        });
      });

    }
  };
})(jQuery);
