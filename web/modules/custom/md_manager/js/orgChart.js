/**
 * @file
 * Behaviors for the search widget in the admin toolbar.
 */
(function ($) {
  Drupal.behaviors.orgChart = {
    attach: function (context, settings) {
      var orgChartData = drupalSettings.mdManager.orgChartData;
      var orgChartNodes = drupalSettings.mdManager.orgChartNodes;

      console.log((orgChartData));
      console.log((orgChartNodes));
      Highcharts.chart('containerXXX', {
        chart: {
          height: 800,
          inverted: true
        },

        title: {
          text: 'Highcharts Org Chart'
        },

        series: [{
          type: 'organization',
          name: 'Highsoft',
          keys: ['from', 'to'],
          data: [["16","17"],["16","18"],["16","21"],["21","22"],["22","26"],["22","25"],["21","23"],["23","29"],["23","28"],["23","27"],["23","31"],["23","30"],["23","32"],["16","19"],["16","20"]],
          nodes: [{"id":"16","title":"16","name":"Directrice G\u00e9n\u00e9rale"},{"id":"17","title":"17","name":"D\u00e9partement Communication et Marketing Public"},{"id":"18","title":"18","name":"D\u00e9partement Qualit\u00e9 et Contr\u00f4le de Gestion"},{"id":"21","title":"21","name":"Directeur G\u00e9n\u00e9ral Adjoint"},{"id":"22","title":"22","name":"Directions support"},{"id":"26","title":"26","name":"Direction des Affaires Administratives et G\u00e9n\u00e9rales"},{"id":"25","title":"25","name":"Direction des Affaires Financi\u00e8res"},{"id":"23","title":"23","name":"Directions techniques"},{"id":"29","title":"29","name":"Direction de la Gestion du Patrimoine et du D\u00e9veloppement Durable"},{"id":"28","title":"28","name":"Direction de la Planification et de la Coordination Territoriale"},{"id":"27","title":"27","name":"Direction des Affaires Techniques"},{"id":"31","title":"31","name":"Direction des Grands Projets"},{"id":"30","title":"30","name":"Direction des Infrastructures Sportives"},{"id":"32","title":"32","name":"Direction des Programmes Sociaux"},{"id":"19","title":"19","name":"D\u00e9partement Audit"},{"id":"20","title":"20","name":"Missions Am\u00e9nagements Provisoires"}],
          colorByPoint: false,
          color: '#007ad0',
          dataLabels: {
            color: 'white'
          },
          borderColor: 'white',
          nodeWidth: 200
        }],
        tooltip: {
          outside: true
        },
        exporting: {
          allowHTML: true,
          sourceWidth: 800,
          sourceHeight: 600
        }

      });
    }
  };
})(jQuery);
