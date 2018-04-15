(function(raw, jStat, d3, nv){
	// A simple histogram
	// The Model
	var model = raw.model();
	
	// The Histogram Chart
	var chartD3 = raw.chart()
		.title("Histogram")
		.description("A histogram is a graphical representation of the distribution of numerical data. It is an estimate of the probability distribution of a continuous variable.")
    .category('Scatter plots')
    .nameFunc('histogram')
    .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/histogram.png")
		.model(model)
  ;
        
	var xD = model.dimension() 
		.title('X Axis')
    .required(1)
		.types(Number);
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
		.defaultValue(50);
                
        var nClases = chartD3.number()
		.title('Classes')
		.defaultValue(10);
                
  var processData = function(data)
  {
            //in case we want to deal with the unaltered data
            //generate the histogram bin'd dataset using d3 histogram methods (which should use x scale defined above?)
            //and generate the CDF values using jStat - https://github.com/jstat/jstat
            var jstat = jStat(data)
              , numBins = nClases()
              , w = width()
              , x = d3.scale.linear().domain([0, numBins]).range([0, w])
              , binData = d3.layout.histogram().bins(x.ticks(numBins))(data)
              , convertedData = []
              , keyName = (typeof(xD.value[0]) != "undefined")? xD.value[0].key: 'Count'
              , countObj = {'key': keyName, 'bar': true, 'color': '#ccf', 'values': []}
              , cdfObj = {'key': 'CDF', 'color': '#333', 'values': []}
              , initClass = 0
              , endClass = 0
              , i = 0;
  
            for (i = 0; i < binData.length; i++)
            {
                if ((initClass === 0) && (binData[i].y > 0))
                {
                    initClass = i;
                    endClass = i;
                }
                else if ((endClass !== 0) && (binData[i].y > 0))
                {
                    endClass = i;
                }
            }
  
            for(i = initClass;  (i <= endClass) && (i < binData.length); i++)
            {
                countObj.values.push([binData[i].x,binData[i].y]);
                cdfObj.values.push([binData[i].x,jstat.normal(jstat.mean(), jstat.stdev()).cdf(binData[i].x)]);      
            }
            
    convertedData.push(countObj);
    convertedData.push(cdfObj);

    return convertedData;
  }
        
  model.map(function (data){
            
    var ret = data.map(function (d){
			return +xD(d);
		});

    ret = processData(ret)
           
    return ret;
	});
        
  chartD3.draw(function (selection, data){
    
    $('#chart').empty();
     
    var m = {top: margin(), right: margin(), bottom: margin(), left: margin()}
    , h = height()
    , w = width()
    , svg = d3.select("#chart").append("svg")
                .attr("width", w)
                .attr("height", h)
                .append("g")
                .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")");

      raw.content.params = {
                   chartId: chartD3.getId(),
                   chartType: 'histogram',
                   options: chartD3.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};


  var c = nv.addGraph(function() {
      var chart = nv.models.linePlusBarChart()
            .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
            .height(h)
            .width(w)
            //We can set x data accessor to use index. Reason? So the bars all appear evenly spaced.
            .x(function(d,i) { return i })
            .y(function(d,i) {return d[1] })
            .focusEnable(false)
            ;
       
      chart.xAxis.tickFormat(function(d) {
        if (typeof(data[0].values[d]) != "undefined")
        {
           return data[0].values[d][0];     
        }
        else
        {
          return '';     
        }
      });
           
      chart.xAxis.ticks(data[0].values.length)

      chart.y1Axis
          .tickFormat(d3.format(',f'));

      chart.y2Axis
          .tickFormat(function(d) { return d3.format('%')(d) });
            
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
})(raw, this.jStat, d3, nv);