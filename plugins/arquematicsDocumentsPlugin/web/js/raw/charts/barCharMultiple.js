(function(raw, d3, nv){
    
    raw.models.set('barCharMultiple', function(){
        
	// The Model
	var model = raw.model();
	
	var chartD3 = raw.chart()
		.title("Bar Chart Stacked/Grouped")
		.description("Displays data grouped or stacked with rectangular bars proportional to the values they represent lengths.")
    .category('Scatter plots')
    .nameFunc('barCharMultiple')
    .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/barCharMultiple.png")
		.model(model)
  ;
                
        // X axis dimension
	// Adding a title to be displayed in the UI
 	// and limiting the type of data to Numbers only
	var x = model.dimension() 
		.title('X Axis')
    .required(1)
		.types(Date,Number)
  ;

	// Y axis dimension
	var y = model.dimension() 
		.title('Y Axis')
    .required(1)
		.types(Number)
  ;
        
  var group = model.dimension()
      .title('Group')
      .types(String, Number, Date)
      .required(1)
  ;
                    
  var label = model.dimension() 
		.title('Group Label')
		.types(String)       
  ;
                
  var showXAxis = chartD3.checkbox()
		.title('Show X Axis')
		.defaultValue(true);
                
  var showYAxis = chartD3.checkbox()
		.title('Show Y Axis')
		.defaultValue(true);
                
  var reduceXTicks = chartD3.checkbox()
		.title('Reduce X Ticks')
		.defaultValue(true);
	// Width
	var width = chartD3.number()
		.title('Width')
		.defaultValue(800);

	// Height
	var height = chartD3.number()
		.title('Height')
		.defaultValue(500);

	// A simple margin
	var margin = chartD3.number()
		.title('Margin')
		.defaultValue(100);
                
  var rotate = chartD3.number()
		.title('Rotate X Ticks')
		.defaultValue(75);
               
  var localization = {
          "stacked-es": "Apiladas",
          "grouped-es": "Agrupadas",
          "stacked-en": "Stacked",
          "grouped-en": "Grouped"
        };

                
  model.map(function (data){  
    var dataRet = []
           , groups = [] //listado de grupos
           , itemObj = {}
           , labelString = false
           , ret = data.map(function (d){
			     return {
                            label: label(d),
                            group: group(d),
                            x: x(d),
                            y: y(d)
			     };
    })
  , dataFormat = (raw.findDataFormat(ret, 'x') !== '')?raw.findDataFormat(ret, 'x'):false;
            
            function selectGroupData(data, group)
            {
                var ret = [];
                
                if (data.length > 0)
                {
                  for (var index = 0; index < data.length; index++)
                  {
                    if (data[index].group === group)
                    {
                      ret.push({x: raw.parseDate(data[index].x, dataFormat),
                                y: data[index].y})     
                    }
                    else
                    {
                      ret.push({x:  raw.parseDate(data[index].x, dataFormat),
                                y: 0})      
                    }
                  }      
                }
                
                return ret;
            }
            
            function selectLabelData(data, group)
            {
                var ret = false;
                
                if (data.length > 0)
                {
                  for (var index = 0; ((!ret) && (index < data.length)); index++)
                  {
                    if (data[index].group === group)
                    {
                      ret = data[index].label;    
                    }
                  }      
                }
                
                return ret;
            }
            
            if (ret.length > 0)
            {
                //saca los grupos
                for (var index = 0; index < ret.length; index++)
                {
                     itemObj = ret[index];
                     if (($.trim(itemObj.group).length > 0) 
                            && (groups.indexOf(itemObj.group) < 0))
                     {
                        groups.push(itemObj.group);
                     }
                } 
                //si tenemos grupos
                if (groups.length > 0)
                {
                    for (var iGroup = 0; iGroup < groups.length; iGroup++)
                    {
                       var groupData = selectGroupData(ret, groups[iGroup]);
                       groupData = groupData.sort(function(x, y) {
                            return x[0] - y[0];
                       });
                        
                       for (var i = 0; i < groupData.length; i++) {
                           groupData[i].x = moment(groupData[i].x).format(dataFormat);
                       }
                        
                     // labelString = selectLabelData(ret, groups[iGroup]);
                      dataRet.push({
                            format: dataFormat,
                            values: groupData,
                            key: (!labelString)?unescape(encodeURIComponent(groups[iGroup])): labelString
                          });  
                    }
                }
            }
            
            return dataRet;
        });
        
        chartD3.draw(function (selection, data)
        {
            $('#chart').empty();
     
            raw.content.params = {
                   chartId: chartD3.getId(),
                   chartType: 'barCharMultiple',
                   //parametros de optiones del modelo en orden
                   options: chartD3.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};
     
            var m = {top: margin(), right: margin(), bottom: margin(), left: margin()}
            , h = height() + m.top  + m.bottom
            , w = width() + m.left + m.right
            , tw = w / 2
            , th = h / 2
    //, isXDataType = x.type() == "Date"
            , svg = d3.select("#chart")
                .append("svg")
                .attr("width", w)
                .attr("height", h)
                .append("g")
                .attr("transform", "translate(" + tw + "," + th + ")")
            ;
                
      
     var c = nv.addGraph(function() {
            var chart = nv.models.multiBarChart()
                .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                .reduceXTicks(reduceXTicks())   //If 'false', every single x-axis tick label will be rendered.
                .showXAxis(showXAxis())
                .showYAxis(showYAxis())
                //.x(function(d) { return d.label })   
                //.y(function(d) { return d.value })
                //.stacked(false)
                //.staggerLabels(true)
                .rotateLabels(rotate())      //Angle to rotate x-axis labels.
                //.showControls(false)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
                .groupSpacing(0.5)    //Distance between each group of bars.
            ;
            
            chart.controlLabels({
                "stacked": localization['stacked-' + arquematics.lang],
                "grouped": localization['grouped-' + arquematics.lang] })
            ;
            
           /*
            chart.xAxis.tickFormat(function(d) { 
                //return d3.time.format("%Y-%m-%d")(d);
                //return isXDataType?raw.multiFormat(d):d3.format('%')(d);
                //return isXDataType?raw.multiFormat(d):d3.format('%')(d);
            });*/
         
/*
            chart.yAxis
                .tickFormat(d3.format(',.1f'));*/

/*
            console.log('data');
            console.log(data);*/
            
                d3.select("#chart svg")
                .attr("width", w)
                .attr("height", h)
                .datum(data)
                .transition()
                .duration(0)
                .call(chart);

                nv.utils.windowResize(chart.update);
        

                return chart;
            });
     
            return c;
        });
        
        return model;
    });
    
  raw.models.barCharMultiple = raw.models.get('barCharMultiple');

  raw.models.barCharMultiple();
  
})(raw, d3, nv);