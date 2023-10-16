/**
 * @file
 * Behaviors for the search widget in the admin toolbar.
 */
(function ($) {
  Drupal.behaviors.orgChart = {
    attach: function (context, settings) {
      var orgChartData = drupalSettings.mdManager.orgChartData;
      var orgChartNodes = drupalSettings.mdManager.orgChartNodes;
      var $levels = drupalSettings.mdManager.levels;
      var result = [];

      for (var i = 0; i < $levels; i++) {
        var levelObj = {
          level: i,
          dataLabels: {
            style: {
              fontSize: '2px'
            }
          }
        };
        result.push(levelObj);
      }

      Highcharts.chart('Orgcontainer', {
        chart: {
          height: 1200,
          inverted: true
        },

        "title": {
          text: ''
        },

        accessibility: {
          point: {
            descriptionFormat: '{add index 1}. {toNode.name}' +
              '{#if (ne toNode.name toNode.id)}, {toNode.id}{/if}, ' +
              'reports to {fromNode.id}'
          }
        },

        series: [{
          type: 'organization',
          "name": ' ',
          keys: ['from', 'to'],
          data: orgChartData,
          nodes: orgChartNodes,
          colorByPoint: false,
          color: '#007ad0',
          dataLabels: {
            color: 'white'
          },
          borderColor: 'white',
          borderRadius:10,
        }],
        tooltip: {
          outside: true
        },
        exporting: {
          allowHTML: true,
          sourceWidth: 1300,
          sourceHeight: 2000
        }

      });
    }
  };
})(jQuery);
