(function(raw, d3, nv){

	var model = raw.model();
        
	var chartD3 = raw.chart()
            .title("Simple Pie Chart")
            .description("")
            .category('Scatter plots')
            .nameFunc('pieChart')
            .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/pieChart.png")
            .model(model)
        ;
        
	var label = model.dimension() 
            .title('Label')
            .required(1)
            .types(String)
        ;

	var size = model.dimension() 
		.title('Percentage or Value')
    .required(1)
		.types(Number)
    ;

	// Width
	var w = chartD3.number()
		.title('Width')
		.defaultValue(900);

	// Height
	var h = chartD3.number()
		.title('Height')
		.defaultValue(600);
                
  var margin = chartD3.number()
		.title('Margin')
		.defaultValue(20);
                
  var showPercent = chartD3.checkbox()
		.title('Show percent')
		.defaultValue(true);

  model.map(function (data){
            
   var labelList = []
   , totalAcum = 0
   , dataRet = []
   , itemObj = {}
   , acumSize = 0
   , ret = data.map(function (d){
            return { label: label(d),
                    size:  size(d)};
    });
                
    if (ret.length > 0)
    {
        for (var index = 0; index < ret.length; index++)
        {
            itemObj = ret[index];
                     
            if (($.trim(itemObj.label).length > 0) 
                && (labelList.indexOf(itemObj.label) < 0))
            {
                labelList.push(itemObj.label);
                acumSize = 0;
                            
               for (var iFind = index;iFind < ret.length; iFind++)
               {
                    if (($.trim(ret[iFind].label).length > 0)
                       && (itemObj.label === ret[iFind].label))
                    {
                        acumSize += parseFloat(ret[iFind].size);  
                    }  
               }
                           
               dataRet.push({label:    itemObj.label,
                            size:     acumSize});
                                      
               totalAcum += acumSize;
           }
        }
                   //pone los porcentajes
        if ((dataRet.length > 0) 
            && showPercent())
        {
            for (var i = 0, percent; i < dataRet.length; i++)
            {
                percent =  dataRet[i].size * 100 / totalAcum;
                dataRet[i].label +=  ': (' + percent.toFixed(2) + '%)';
            }      
        }
  }
                     
 return dataRet;
});
        
chartD3.draw(function (selection, data){
            
    $('#chart').empty();
   
    var m = {top: margin(), right: margin(), bottom: margin(), left: margin()}   
    , width = w()
    , height = h();
                
             /*
             console.log('raw.content');
             console.log(raw.content);*/
               // radius = Math.min(width, height) / 2;

            /*
            var color = d3.scale.ordinal()
                        .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);
                  */
                 
            //colors.domain(data, function(d){ return d.color; });
            
            /*var color = d3.scale.ordinal()
                     .range(colors);*/
            
            /*
            var parseColors = function(d) { 
                console.log('colors()(d.data.label)');
                console.log(colors()(d.data.label));
                return colors()(d.data.label);
            }*/
            
            
        var svg = d3.select("#chart")
                .append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        raw.content.params = {
                   chartId: chartD3.getId(),
                   chartType: 'pieChart',
                   //parametros de optiones del modelo en orden
                   options: chartD3.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};

           
        var c =  nv.addGraph(function() {
                var chart = nv.models.pieChart()
                            .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                            .x(function(d) { return d.label })
                            .y(function(d) { return d.size })
                            //.style("fill", function(d) { return colors()(d.data.label); })
                            .showLabels(true);
                     //para poner colores con esto      
                    //chart.color(["#FF0000","#00FF00","#0000FF"])
                    //
                    
                    d3.select("#chart svg")
                        .attr("width", width)
                        .attr("height", height)
                        .datum(data)
                        .transition()
                        .duration(350)
                        .call(chart);
   
                    
               return chart;
            });
            
            return c;
	});

})(raw, d3, nv);