/* Directives */

// mirar esto para las series de tiempo
//http://n3-charts.github.io/line-chart/#/examples

angular.module('raw.directives', [])
	.directive('chart',['$rootScope', 'dataService', function ($rootScope, dataService)
          {
	    return {
	      restrict: 'A',
	      link: function postLink(scope, element, attrs) {

	        function update(){

	        	$('*[data-toggle="tooltip"]').tooltip({ container:'body' });

	        	d3.select(element[0]).select("*").remove();

	        	if (!scope.chart || !scope.data.length) return;
			if (!scope.model.isValid()) return;

	        	d3.select(element[0])
	        		.append("svg")
	        		.datum(scope.data)
	        		.call(scope.chart)

	    			scope.svgCode = d3.select(element[0])
	        			.select('svg')
                                        .attr("version", 1.1)
                                        .attr("encoding", "UTF-8")
	    				.attr("xmlns", "http://www.w3.org/2000/svg")
	    				.node().parentNode.innerHTML;
	    			
	    			$rootScope.$broadcast("completeGraph");

	        }

	        scope.delayUpdate = dataService.debounce(update, 300, false);

	        scope.$watch('chart', update);
	        scope.$on('update', update);
	        //scope.$watch('data', update)
	        scope.$watch(function(){ if (scope.model) return scope.model(scope.data); }, update, true);
	        scope.$watch(function(){ if (scope.chart) return scope.chart.options().map(function (d){ return d.value }); }, scope.delayUpdate, true);

	      }
	    };
	  }])

	.directive('chartOption', function () {
	    return {
	      restrict: 'A',
	      link: function postLink(scope, element, attrs) {

	        element.find('.option-fit').click(function(){
	        	scope.$apply(fitWidth);
	        });

	        scope.$watch('chart', fitWidth);

	        function fitWidth(chart, old){
	        	if (chart == old) return;
	        	if(!scope.option.fitToWidth || !scope.option.fitToWidth()) return;
	        	scope.option.value = $('.col-lg-9').width();
	        }

	        $(document).ready(fitWidth);

	      }
	    };
	  })

	.directive('colors',['$rootScope', function ($rootScope) {
	    return {
	      restrict: 'A',
	      templateUrl : '/arquematicsDocumentsPlugin/js/raw/templates/colors.html',
	      link: function postLink(scope, element, attrs) {

	        scope.scales = [ 
	        	
	        	{
	        		type : 'Ordinal (categories)',
	        		value : d3.scale.ordinal().range(raw.divergingRange(1)),
	        		reset : function(domain){ this.value.range(raw.divergingRange(domain.length || 1)); },
	        		update : ordinalUpdate
	        	},
	        	/*{
	        		type : 'Ordinal (max 20 categories)',
	        		value : d3.scale.category20(),
	        		reset : function(){ this.value.range(d3.scale.category20().range().map(function (d){ return d; })); },
	        		update : ordinalUpdate
	        	},
	        	{
	        		type : 'Ordinal B (max 20 categories)',
	        		value : d3.scale.category20b(),
	        		reset : function(){ this.value.range(d3.scale.category20b().range().map(function (d){ return d; })); },
	        		update : ordinalUpdate
	        	},
	        	{
	        		type : 'Ordinal C (max 20 categories)',
	        		value : d3.scale.category20c(),
	        		reset : function(){ this.value.range(d3.scale.category20c().range().map(function (d){ return d; })); },
	        		update : ordinalUpdate
	        	},
	        	{
	        		type : 'Ordinal (max 10 categories)',
	        		value : d3.scale.category10(),
	        		reset : function(){ this.value.range(d3.scale.category10().range().map(function (d){ return d; })); },
	        		update : ordinalUpdate
	        	},*/
	        	/*{
	        		type : 'Linear (numeric)',
	        		value : d3.scale.linear().range(["#f7fbff", "#08306b"]),
	        		reset : function(){ this.value.range(["#f7fbff", "#08306b"]); },
	        		update : linearUpdate
	        	}*/
	        ];

	        function ordinalUpdate(domain) {
	        	if (!domain.length) domain = [null];
	        	this.value.domain(domain);
	        	listColors();
	        }

	        function linearUpdate(domain) {
	        	domain = d3.extent(domain, function (d){return +d; });
	        	if (domain[0]==domain[1]) domain = [null];
	        	this.value.domain(domain).interpolate(d3.interpolateLab);
	        	listColors();
	        }

	        scope.setScale = function(){
	        	scope.option.value = scope.colorScale.value;
	        	scope.colorScale.reset(scope.colorScale.value.domain());
	        	$rootScope.$broadcast("update");
	        }

	        function addListener(){
	        	scope.colorScale.reset(scope.colorScale.value.domain());
	        	scope.option.on('change', function (domain){
		      		scope.option.value = scope.colorScale.value;
		      		scope.colorScale.update(domain);
		      	})
	        }

	        scope.colorScale = scope.scales[0];

	        scope.$watch('chart', addListener)
					scope.$watch('colorScale.value.domain()',function (domain){
						scope.colorScale.reset(domain);
						listColors();
					}, true);

	        function listColors(){
	        	scope.colors = scope.colorScale.value.domain().map(function (d){
	        		return { key: d, value: scope.colorScale.value(d)}
	        	}).sort(function (a,b){
	        		if (raw.isNumber(a.key) && raw.isNumber(b.key)) return a.key - b.key;
	        		return a.key < b.key ? -1 : a.key > b.key ? 1 : 0;
    				})
	        }

	        scope.setColor = function(key, color) {
	          var domain = scope.colorScale.value.domain(),
	          		index = domain.indexOf(key),
	          		range = scope.colorScale.value.range();
	          range[index] = color;	         	
						scope.option.value.range(range);
	          $rootScope.$broadcast("update");
	        }

	        scope.foreground = function(color){
	        	return d3.hsl(color).l > .5 ? "#000000" : "#ffffff";
	        }

	        scope.$watch('option.value', function (value){
	        	if(!value) scope.setScale();
	        })
	        

	      }
	    };
	  }])

.directive('sortable', ['$compile','$rootScope', 'gettextCatalog', function ($compile, $rootScope, gettextCatalog)  {
    return {
      restrict: 'A',
      scope : {
      	title : "=",
      	value : "=",
        initValue: "=",
        index : "=",
      	types : "=",
      	multiple : "=",
        dimension: "="
      },
      /*
      template:'<div class="msg">{{messageText}}</div>'
        + '<li ng-repeat="dimension in dimension.value"  class="dimension" data-index="{{$index}}" data-dimension="{{dimension}}">'
	+   '<span class="dimension-key">{{ dimension.key }}</span>'
	+   '<span class="dimension-type">{{dimension.type | translate }}</span>'
	+   '<span  class="remove pull-right">×</span>'
	+ '</li>'
      ,*/
      
      compile: function(tElement, tAttrs) {
        
        var templateLoad = '<div class="msg">{{messageText}}</div>'
          , templateItem = '<li ng-repeat="dimension in value"  class="dimension" data-init_load="true" data-index="{{$index}}" data-dimension="{{dimension}}">'
                        +       '<span class="dimension-key">{{ dimension.key }}</span>'
                        +       '<span class="dimension-type">{{dimension.type | translate }}</span>'
                        +       '<span ng-click="removeDimension($event, dimension, $index)" class="remove pull-right">&times;</span>'
                        +  '</li>';
          
        

        return function (scope, element, attrs) {

            var el = angular.element(templateLoad)
            , elemList = angular.element(templateItem)
            , removeLast = false
            , addTemplateItem = true;
            
            //
            $compile(el)(scope);
            element.append(el);
            
            if (addTemplateItem
                && raw.content.params
                && (scope.index < raw.content.params.dimension.length))
            { 
               $compile(elemList)(scope);
               element.append(elemList);
               
               addTemplateItem = false;
            }
            
            element.sortable({
	        items : '> li',
	        connectWith: '.dimensions-container',
	        placeholder:'drop',
	        start: onStart,
	        update: onUpdate,
	        receive : onReceive,
	        remove: onRemove,
	        over: over,
	        tolerance:'intersect'
	      });
              
              scope.$watch('value', function (value){
		    	if (!value.length) {
                            element.find('li').remove();
		    	}
                        message();
              });
              
              scope.removeDimension = function($event, dimension, index)
              {
                
                $($event.currentTarget)
                    .parents('li').remove();
                    
                scope.value = values();
                removeLast = true;
              }
           
             function over(e,ui){
		    	var dimension = ui.item.data().dimension,
		    	
                        html = isValidType(dimension) ? '<i class="fa fa-arrow-circle-down breath-right"></i>' + gettextCatalog.getString('Drop here') : '<i class="fa fa-times-circle breath-right"></i>' + gettextCatalog.getString("Don't drop here");
		    	element.find('.drop').html(html);
	      }

              function onStart(e,ui){
		    	var dimension = ui.item.data().dimension,
		    	
                        html = isValidType(dimension) ? '<i class="fa fa-arrow-circle-down breath-right"></i>' +  gettextCatalog.getString('Drop here'): '<i class="fa fa-times-circle breath-right"></i>' + gettextCatalog.getString("Don't drop here");
		    	element.find('.drop').html(html);
                        element.parent().css("overflow","visible");
		     	angular.element(element).scope().open=false;
              }

              function onUpdate(e,ui){
                    
                    ui.item.find('.dimension-icon').remove();

                    if (ui.item.find('span.remove').length == 0)
                    {
		      	ui.item.append("<span class='remove pull-right'>&times;</span>")
		    }
                    
		    ui.item.find('span.remove').click(function(){  
                        ui.item.remove();
                        onRemove(); 
                    });

		    if (removeLast) {
                        ui.item.remove();
		     	removeLast = false;
		    }    	

		    scope.value = values();
		    scope.$apply();

		    element.parent().css("overflow","hidden");

                    var dimension = ui.item.data().dimension;
		     	ui.item.toggleClass("invalid", !isValidType(dimension))
		     	message();

		    $rootScope.$broadcast("update");
            }

            function onReceive(e,ui) {
                
			var dimension = ui.item.data().dimension;

		     	removeLast = hasValue(dimension);

			if (!scope.multiple && scope.value.length)
                        {
                            var found = false;
                            element.find('li').each(function (i,d) {
		     			if ($(d).data().dimension.key == scope.value[0].key && !found) {
		     				$(d).remove();
		     				found = true;
		     				removeLast=false;
		     			}
                                    });
		     	}
		    	scope.value = values();
			ui.item.find('span.remove').click(function(){  
                            ui.item.remove();
                            onRemove(); 
                        });
                        /*
                        if (removeLast)
                        {
                          ui.item.find('span.remove').click();     
                        }*/
            }

            function onRemove(e,ui) {
		    	scope.value = values();
		    	scope.$apply();
            }

            function values(){
                        if (!element.find('li').length) return [];
                            var v = []
                            , dimensionKeys = []
                            , data;

                            element.find('li').map(function (i,d)
                            { 
                                data = $(d).data();

                                if (data.dimension 
                                    && (data.dimension !== '{{dimension}}'))
                                {
                                   v.push(data.dimension);      
                                }
                                else if (data.dimension 
                                        && data.dimension.key
                                        && (dimensionKeys.indexOf(data.dimension.key) < 0))
                                {
                                   dimensionKeys.push(data.dimension.key);      
                                }
                                else if (data.init_load)
                                {
                                   $(d).data('init_load', false); 
                                }
                                else if ((!data.init_load) && (!addTemplateItem))
                                {
                                   d.remove();     
                                }
                            });
                           
			return v;
            }

            function hasValue(dimension){

                        for (var i=0; i<scope.value.length;  i++)
                        {
                            if (scope.value[i].key == dimension.key) {
				return true;
                            }
                        }
                        return false;
             }

             function isValidType(dimension) {
                        if (!dimension) return;

                        if ('{{dimension}}' === dimension)
                        {
                          return true;      
                        }
                        else
                        {
                         return scope.types
                                    .map(function (d){ return d.name; })
                                    .indexOf(dimension.type) != -1;       
                        }
					
             }

             function message(){
			var hasInvalidType = values().filter(function (d){ return !isValidType(d); }).length > 0;
			
                        scope.messageText = hasInvalidType
                            ? gettextCatalog.getString("You should only use " + scope.types.map(function (d){ return d.name.toLowerCase() + "s"; }).join(" or ") + " here")
                            : gettextCatalog.getString("Drag " + scope.types.map(function (d){ return d.name.toLowerCase() + "s"; }).join(", ") + " here");
             }
        };
      }
    }
   }])

.directive("selectanalysis", ['$rootScope', function ($rootScope) {

return {
	      restrict: 'E',
              replace:true,
              template :  '<select id="selectanalysis" data-placeholder="{{\'Choose a Chart\' | translate}}" class="char-chosen-select">'
                          +'<option>{{\'Choose a Chart\' | translate }}</option>'
                          +'<optgroup label="{{category | translate }}" ng-repeat="category in categories">'
                          + '<option value="{{c.nameFunc()}}" data-chart-name="{{c.nameFunc()}}" data-chart-id="{{c.getId()}}" data-thumbnail="{{ c.thumbnail() }}"  ng-class="{selected: c == chart}" ng-repeat="c in filterByCat(category) track by $index">'
                          + '{{c.title() | translate }}'
                          +'</option>' 
                          +'</optgroup>' 
                          +'</select>',
	      link: function postLink($scope, element, attrs, ngModel)
              {
                  
                  $('#selectanalysis').on( "change", function(e)
                  {
                      e.preventDefault();
                      
                      var nameFunc = $(this).val();
                      
                      //try
                      //{
                        if ((!$scope.selectedChart)
                            || (!$scope.chart) 
                            || (nameFunc !== $scope.chart.nameFunc()))
                        { 
                            $scope.selectChart(nameFunc);
                        } 
                      //} catch(e){
                      //  console.log(e);
                      //}
                      
                      
                      return false;
                  });  
              }
  }
}])
/*
.directive("selectedit", ['$rootScope', function ($rootScope) {

return {
	      restrict: 'E',
              replace:true,
              template :  '',
	      link: function postLink($scope, element, attrs, ngModel)
              {
                  
              	  console.log('#selectanalysis');
                  console.log($scope.content);*/
                  
                  //$scope.selectChart(raw.content.params.chartType);
                  /*
                  $('#selectanalysis').on( "change", function(e)
                  {
                      e.preventDefault();
                      
                      var nameFunc = $(this).val();
                      
                      //$scope.selectedChart = true;
                      console.log('nameFunc');
                      console.log(nameFunc);
                      if (nameFunc == $scope.chart.nameFunc())
                      {
                         return;     
                      }
                      else
                      {
                          $scope.model.clear();
                          $scope.selectChart(nameFunc);     
                      }
                  });

                  $('#selectanalysis').change();
                      
              }
  }
}])*/

.directive('draggable', function () {
	    return {
	      restrict: 'A',
	      scope:false,
	    //  templateUrl : 'templates/dimensions.html',
	      link: function postLink(scope, element, attrs)
              {
		      scope.$watch('metadata', function(metadata){
		      	if (metadata && (!metadata.length))
                        {
                           element.find('li').remove();     
                        }
                            
			element.find('li').draggable({
			        connectToSortable:'.dimensions-container',
                                helper : 'clone',
			        revert: 'invalid',
			        start : onStart
			});
		     });

                     function onStart(e,ui){
			      ui.helper.width($(e.currentTarget).width());
			      ui.helper.css('z-index','100000');
                     }
	      }
	    }
	   })

.directive('group', function () {
    return {
      restrict: 'A',
      link: function postLink(scope, element, attrs) {
        scope.$watch(attrs.watch, function (watch){
          var last = element;
          element.children().each(function(i, o){
            if( (i) && (i) % attrs.every == 0) {
           	  var oldLast = last;
              last = element.clone().empty();
              last.insertAfter(oldLast);
            }
            $(o).appendTo(last);
          });

        },true)

       }
      };
})
.directive('rawTable',['gettextCatalog', function (gettextCatalog) {
  return {
    restrict: 'A',
    link: function postLink(scope, element, attrs) {

    	var sortBy,
    	descending = true;

    	function update(){

    		d3.select(element[0]).selectAll("*").remove();

    		if(!scope.data|| !scope.data.length) {
    			d3.select(element[0]).append("span").text(gettextCatalog.getString('Please, review your data'))
    			return;
    		}

    		var table = d3.select(element[0])
    			.append('table')
    			.attr("class","table table-striped table-condensed")

    		if (!sortBy) sortBy = scope.metadata[0].key;

    		var headers = table.append("thead")
    			.append("tr")
					.selectAll("th")
					.data(scope.metadata)
					.enter().append("th")
						.text( function(d){ return d.key; } )
						.on('click', function (d){ 
							descending = sortBy == d.key ? !descending : descending;
							sortBy = d.key;
							update();
						})
				
				headers.append("i")
					.attr("class", function (d){ return descending ? "fa fa-sort-desc pull-right" : "fa fa-sort-asc pull-right"})
					.style("opacity", function (d){ return d.key == sortBy ? 1 : 0; })

				var rows = table.append("tbody")
					.selectAll("tr")
					.data(scope.data.sort(sort))
					.enter().append("tr");

				var cells = rows.selectAll("td")
					.data(d3.values)
					.enter().append("td");
					cells.text(String);

    	}

    	function sort(a,b) {
    		if (raw.isNumber(a[sortBy]) && raw.isNumber(b[sortBy])) return descending ? a[sortBy] - b[sortBy] : b[sortBy] - a[sortBy];
	      return descending ? a[sortBy] < b[sortBy] ? -1 : a[sortBy] > b[sortBy] ? 1 : 0 : a[sortBy] < b[sortBy] ? 1 : a[sortBy] > b[sortBy] ? -1 : 0;
    	}

    	scope.$watch('data', update);
    	scope.$watch('metadata', function(){
    		sortBy = null;
    		update();
    	});

    }
  };
}])

.directive('copyButton', function () {
  return {
    restrict: 'A',
    link: function postLink(scope, element, attrs) {

    	ZeroClipboard.config({ moviePath: "/arquematicsDocumentsPlugin/js/raw/bower_components/zeroclipboard/ZeroClipboard.swf" });

	    var client = new ZeroClipboard( element );

	    client.on( "load", function (client) {
	      client.on( "complete", function (client, args) {
	        element.tooltip('destroy');
	        element.tooltip({ title:'Copied!'});
	        element.tooltip('show')
	      } );
	    });

	    client.on( 'mouseover', function ( client, args ) {
	    	element.tooltip({title:'Copy to clipboard'});
			  element.tooltip('show');
			});

			client.on( 'mouseout', function ( client, args ) {
			  element.tooltip('destroy');
			});

    }
  };
})

.directive('coder', function () {
  return {
    restrict: 'EA',
    template :  '<textarea id="source" readonly class="source-area" rows="4" ng-model="svgCode"></textarea>',
    link: function postLink(scope, element, attrs) {

    	scope.$on('completeGraph',function(){
    		element.find('textarea').val(scope.svgCode)
    	})

      /*function asHTML(){
        if (!$('#chart > svg').length) return "";
        return d3.select('#chart > svg')
        	.attr("xmlns", "http://www.w3.org/2000/svg")
        	.node().parentNode.innerHTML;
      }

      scope.$watch(asHTML, function(){
        scope.html = asHTML();
      },true)
      scope.$on('update', function(){
      	scope.html = asHTML();
      })*/
    }
  };
})

.directive('instructogo', function () {
    return {
    restrict: 'E',
    replace:true,
    template : '<span data-ng-show="showIns" ng-repeat="data in collectionInsData">' +
               ' {{data}}' +
               '</span>'
    ,
    link: function postLink(scope, element, attrs) {
       
       scope.showIns = false;
       
       scope.$watch(
            function() {
                if (scope.model)
                {
                  return scope.model.instruction();      
                }
                else return false;
            },
            function (collection){
             if (collection && (collection.length > 0))
             {
                scope.collectionInsData = collection;
                scope.showIns = true;     
             }
             else
             {
               scope.collectionInsData = [];
               scope.showIns = false;         
             }
       });
       
    }
  };
})
.directive('downloader', ['$q', function ($q) {
 return {
      restrict: 'E',
      replace:true,
      template :  '<div class="row">' +
                      '<button ng-click="saveSvg()" class="btn btn-success form-control" ng-class="{disabled:saving}">{{ \'Save and exit\' | translate}}</button>' +
                  '</div>',

      link: function postLink(scope, element, attrs) {
        scope.saving = false;
        
        scope.saveSvg = function ()
        {
          scope.saving = true; 
          
          var dataImage = d3.select("#chart > svg")
                                       .attr("version", 1.1)
                                       .attr("encoding", "UTF-8")
                                       .attr("xmlns", "http://www.w3.org/2000/svg")
                                       .node()
                                       .parentNode.innerHTML
           , $form = $('#form-diagram')
           , $controlGroup = $('#note_title').parents('.input-group')
           , titleText =  $.trim($('#note_title').val())
           , pass = (!scope.pass)?arquematics.utils.randomKeyString(50):scope.pass
           , seen = []
           , dataJson = JSON.stringify(raw.content, function(key, val) {
                        if (val != null && typeof val == "object")
                        {
                            if (seen.indexOf(val) >= 0) {
                                return;
                            }
                            seen.push(val);
                        }
                    return val;
            })
           , dataImageSvgToPNG = function (dataImage)
           {
               var deferred = $q.defer()
               , img = new Image();
               
               img.onload = function() {
                   var canvas = document.createElement("canvas")
                   , ctx = canvas.getContext("2d");
                   
                   canvas.width = this.width;
                   canvas.height = this.height;
                   ctx.drawImage(img, 0, 0);
                   
                   deferred.resolve(canvas.toDataURL("image/png"));
               };
               
               img.onerror = function() {
                    deferred.reject();
               };
               
               img.src = dataImage;
               
               
               return deferred.promise;
           }
           , callBack = function (formData) {
                        $.ajax({
                            type: (scope.pass)?"PUT":"POST",
                            url:  $form.attr('action'),
                            datatype: "json",
                            contentType: "application/x-www-form-urlencoded",
                            data: formData,
                            cache: false,
                            success: function(dataJSON)
                            {
                              //scope.saving  = false; 
                              window.location = $('#takeBack').attr('href'); 
                            },
                            error: function() 
                            {
                              //scope.saving  = false; 
                              window.location = $('#takeBack').attr('href');
                            }
                        });
                     };
          
          if (titleText.length === 0)
          {
             scope.saving = false;
             
             $controlGroup.addClass('has-error');
             
             $('#note_title').focus();
             
          }
          else if (titleText.length > 0)
          {
              scope.saving = true; 
              
              $('body').addClass('loading');
              
              $controlGroup.removeClass('has-error');
              
              dataImage = 'data:image/svg+xml;charset=utf-8,' + arquematics.codec.encodeURIData(dataImage);
              
             var promise = dataImageSvgToPNG(dataImage);
             
             promise.then(function(base64Image) {
                  
                 if (arquematics.crypt)
                 { 
                    var data = {
                    //en el editor nunca se comparte
                        "note[share]"            :0,
                        "note[trash]"            :$('#note_trash').val(),
                        "note[is_favorite]"      :$('#note_is_favorite').val(),
                        "note[pass]"             :pass,
                        "note[title]"            : arquematics.simpleCrypt.encryptBase64(pass ,titleText),
                        "note[_csrf_token]"      :$('#note__csrf_token').val(),
                        "note[type]"             :'rawchart',
                        "note[data_image]"       :arquematics.simpleCrypt.encryptBase64(pass , base64Image),
                        "note[content]"          :arquematics.simpleCrypt.encryptBase64(pass , dataJson)
                };
                
                arquematics.utils.encryptDataAndSend(data, callBack, 'note[pass]');        
              }
              else
              {
                 scope.saving = true; 
              
                 $controlGroup.removeClass('has-error');
              
                 $('#note_data_image').val(base64Image);
                 $('#note_content').val(dataJson);
                 $('#note_type').val('rawchart');
                          
                 arquematics.utils.prepareFormAndSend($form, callBack);     
              }
  
             }, function(reason) {
                
                //alert('Failed: ' + reason);
             }, function(update) {
                
             });
          }
        }
        
        $('#editor_save').on( "click", function(e) {
            e.preventDefault();
            scope.saveSvg()
        });
        
        $('#takeBack').on( "click", function(e) {
            $('body').addClass('loading');
            window.location = $('#takeBack').attr('href');
        });
      }
 }
}]);
