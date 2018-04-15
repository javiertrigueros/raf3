(function(raw, d3, nv){
	var model = raw.model();
	
	var chartD3 = raw.chart()
		.title("Bar Chart")
		.description("Presents grouped data with rectangular bars with lengths proportional to the values that they represent.")
        .category('Scatter plots')
        .nameFunc('barChar')
        .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/barchar.png")
		.model(model)
    ;
        
    var label = model.dimension() 
		.title('Label')
        .required(1)
		.types(String);

	var y = model.dimension() 
		.title('Y Axis')
        .required(1)
		.types(Number);
        
    var showXAxis = chartD3.checkbox()
		.title('Show X Axis')
		.defaultValue(true);
                
    var showYAxis = chartD3.checkbox()
		.title('Show Y Axis')
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
        
        
        function getGroups(data)
        {
            var groups = [] //listado de grupos
            , itemObj = {};
                
            if (data.length > 0)
            {
                //saca los grupos
                for (var index = 0; index < data.length; index++)
                {
                     itemObj = data[index];
                     if (($.trim(itemObj.label).length > 0) 
                            && (groups.indexOf(itemObj.label) < 0))
                     {
                        groups.push(itemObj.label);
                     }
                }      
            }
            return groups;
        }
        
        model.map(function (data){
            
           var ret = data.map(function (d){
                    return {
                        label: label(d),
                        value: +y(d) 
                    }      
		      })
             , groups = getGroups(ret)
             , resolveData =  {key: "Cumulative Return",
                               values: []};
             
           if ((groups.length > 0)
                 && (ret.length > 0)) 
           {
                for (var index = 0; index < groups.length; index++)
                {
                    var acum = 0;
                    for (var iData = 0; iData < ret.length; iData++)
                    {
                        if (ret[iData].label == groups[index])
                        {
                            acum += ret[iData].value;   
                        }
                    }
                    
                    resolveData.values.push({
                        label: groups[index],
                        value: acum
                    });
                } 
            }
            
            resolveData = [resolveData]; 


            return resolveData;
	});

chartD3.draw(function (selection, data)
{
     $('#chart').empty();
    
     var m = {top: margin(), right: margin(), bottom: margin(), left: margin()}
    , h = height() + m.top  + m.bottom
    , w = width() + m.left + m.right
    , showX = showXAxis()
    , showY = showYAxis()
    , svg = d3.select("#chart").append("svg")
                .attr("width", w)
                .attr("height", h)
                .append("g")
                .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");
                
    raw.content.params = {
                   chartId: chartD3.getId(),
                   chartType: 'barChar',
                   //parametros de optiones del modelo en orden
                   options: chartD3.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};
     
   var c = nv.addGraph(function() {
        var chart = nv.models.discreteBarChart()
                    .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                    .x(function(d) { return d.label })   
                    .y(function(d) { return d.value })
                    .showXAxis(showX)
                    .showYAxis(showY)
                    .staggerLabels(true)
        ;
        
        chart.yAxis.tickFormat(function(d) {
            if (d % 1 != 0)
            {
               return d;     
            }
            else
            {
               return d3.format(",d")(d);	
            }
        });
        
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
 
 
})(raw, d3, nv);