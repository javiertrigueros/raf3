/* Controllers */

angular.module('raw.controllers', [])

.controller('RawCtrlEditData',[ '$rootScope', '$scope', '$window', 'dataService', 'gettextCatalog', function ($rootScope, $scope, $window, dataService, gettextCatalog) {

    // init
    $scope.selectedChart = false;
    $scope.raw = raw;
    $scope.data = [];
    $scope.metadata = [];
    $scope.error = false;
    $scope.loading = true;
    $scope.charts = raw.charts.values().sort(function (a,b){ return a.title() < b.title() ? -1 : a.title() > b.title() ? 1 : 0; });
    $scope.chart = $scope.charts[0];
    $scope.model = $scope.chart.model();
    

    //añadir grafico simples
    //http://nvd3.org/index.html
    // Mirar graficos que ofrece office
    //https://support.office.com/es-es/article/Tipos-de-gr%C3%A1ficos-disponibles-a6187218-807e-4103-9e0a-27cdb19afb90#bmstockcharts
    $scope.categories = ['Scatter plots', 'Time Series', 'Quality control', 'Regression', 'Distributions',  'Others'];

    $scope.samples = [
      { title : gettextCatalog.getString('Cars (multivariate)'),        url : '/arquematicsDocumentsPlugin/js/raw/data/multivariate.csv' },
      { title : gettextCatalog.getString('Movies (dispersions)'),       url : '/arquematicsDocumentsPlugin/js/raw/data/dispersions.csv' },
      { title : gettextCatalog.getString('Music (flows)'),              url : '/arquematicsDocumentsPlugin/js/raw/data/flows.csv' },
      { title : gettextCatalog.getString('Cocktails (correlations)'),   url : '/arquematicsDocumentsPlugin/js/raw/data/correlations.csv' },
      { title : gettextCatalog.getString('Votation (percent)'),         url : '/arquematicsDocumentsPlugin/js/raw/data/partidos.csv' },
      { title : gettextCatalog.getString('Groups (Time series)'),       url : '/arquematicsDocumentsPlugin/js/raw/data/groups.csv' }
    ];

    $scope.$watch('sample', function (sample){
      if (!sample) return;
      dataService.loadSample(sample.url).then(
        function(data){
          $scope.text = data;
        }, 
        function(error){
          $scope.error = error;
        }
      );
    });
    
    $rootScope.$watch('pass', function (pass){
      //carga los datos
      if (pass)
      {
        $scope.info = {pass: $scope.pass,
                     title: $scope.title ,
                     content: $scope.content};     
      
        raw.content = $scope.info.content;
      
        //cambia el titulo del formulario
        $('#note_title').val($scope.info.title);
        //mete el texto
        $scope.text = $scope.info.content.text;
        raw.hasLoadData = false;
      }
      else
      {
          raw.content = false;
          $scope.info = false;
          raw.hasLoadData = false;
      }
    });

    $scope.selectChart = function(chartName)
    {
      console.log('chartName');
      console.log(chartName);
      
      var i = 0
      , find = false
      , findIndex = -1;
      for (; (!find) && (i < $scope.charts.length); i++)
      {
          find = ($scope.charts[i].nameFunc() == chartName); 
          if (find)
          {
            findIndex = i;   
          }
      }
      
      if (find)
      {  
        $scope.selectedChart = true;
        
        $scope.chart = $scope.charts[findIndex];
        $scope.model = $scope.chart.model();
        $scope.dimensions =  $scope.model.dimensions().values();

        $scope.model.setDimensionData(raw.content.params.dimension);

        var iOption = 0; 
        $scope.chart.options().map(function (d){ 
          d.value = raw.content.params.options[iOption];
          iOption++;
          return d.value;
        });

        
        $('.dimensions-wrapper').each(function (e){
            if (angular.element(this) 
               && (typeof angular.element(this).scope === "function"))
            {
               try {
                angular.element(this).scope().open = false;
                angular.element(this).scope().$apply();  
               } catch(e){}
            }
        });
      }
    }

    $scope.$watch('error', function (error){
      if (!$('.CodeMirror')[0]) return;
      var cm = $('.CodeMirror')[0].CodeMirror;
      if (!error) {
        cm.removeLineClass($scope.lastError,'wrap','line-error');
        return;
      }
      cm.addLineClass(error, 'wrap', 'line-error');
      cm.scrollIntoView(error);
      $scope.lastError = error;

    });

    $scope.parse = function(text){

      if ($scope.model) 
      {
         $scope.model.clear();
      }
          
      $scope.data = [];
      $scope.metadata = [];
      $scope.dimensions = [];
      $scope.error = false;
      $scope.$apply();

      try {
        var parser = raw.parser();
        $scope.data = parser(text);
        $scope.metadata = parser.metadata(text);
        $scope.dimensions =  $scope.model.dimensions().values();
        
        if (raw.content)
        {
            $scope.selectChart(raw.content.params.chartType);
        }
        
        $scope.error = false;
      } catch(e){
        $scope.data = [];
        $scope.metadata = [];
        $scope.dimensions = [];
        $scope.error = e.name == "ParseError" ? +e.message : false;
      }
      $scope.loading = false;
    }

    $scope.delayParse = dataService.debounce($scope.parse, 500, false);

    $scope.$watch("text", function (text){
      $scope.loading = true;
      $scope.delayParse(text);
    });

    

    $scope.filterByCat = function(cat)
    {
        var ret = []
        
        if ($scope.charts.length > 0)
        {
            for (var i = 0; i < $scope.charts.length; i++)
            {
                if ($scope.charts[i].category() === cat)
                {
                   ret.push($scope.charts[i]);     
                }   
            }
            
            ret = ret.sort(function(a, b){
                if(a.firstname < b.firstname) return -1;
                if(a.firstname > b.firstname) return 1;
                return 0;
            });
            
        }
        return ret;
    }

    $scope.codeMirrorOptions = {
      lineNumbers : true,
      lineWrapping : true,
      placeholder : gettextCatalog.getString('Paste your text or drop a file here.')
    }

  }])