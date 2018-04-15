(function(){

        // http://nvd3.org/examples/line.html Line char
        // 
        // http://bl.ocks.org/mbostock/3883245 sample at mbostock 
        // 
	// Simple Line Chart

	// The Model
        /*
fecha           cierre	grupo
01-03-2014	582.13	grupo1
02-03-2014	583.98	grupo1
03-03-2014	603.00	grupo1
04-03-2014	607.70	grupo2
05-03-2014	610.00	grupo2
06-03-2014	560.28	grupo2
07-03-2014	571.70	grupo1
08-03-2014	572.98	grupo1
09-03-2014	587.44	grupo1
10-03-2014	608.34	grupo1
11-03-2014	609.70	grupo1
12-03-2014	580.13	grupo2
     */

	var model = raw.model();
        
        // The Chart

	var chart = raw.chart()
		.title("Simple Line Chart")
		.description("")
                .category('Scatter plots')
                .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/simpleLineChart.png")
		.model(model);


	// X axis dimension
	// Adding a title to be displayed in the UI
 	// and limiting the type of data to Numbers only
	var x = model.dimension() 
		.title('X Axis')
                .required(1)
		.types(Date);

	// Y axis dimension
	// Same as X
	var y = model.dimension() 
		.title('Y Axis')
                .required(1)
		.types(Number);
                
        var group = model.dimension() 
                    .title('Group');

	// Width
	var w = chart.number()
		.title('Width')
		.defaultValue(900);

	// Height
	var h = chart.number()
		.title('Height')
		.defaultValue(600);

                    
        var xL = chart.stringOption()
		.title('X Axis Label')
                .defaultValue('');
        
        var yL = chart.stringOption()
		.title('Y Axis Label')
                .defaultValue('');
                
        model.map(function (data){
            
           
           var dataRet = []
           , groups = [] //listado de grupos
           , itemObj = {}
           , ret = data.map(function (d){
			return {
                            group: ($.trim(group(d)).length > 0)?group(d): false,
                            x: x(d),
                            y: y(d)
			};
            })
            
            , dataFormat = raw.findDataFormat(ret, 'x');
            
            function parseDate(value)
            {
                if (!dataFormat)
                {
                    return value;
                }
                else
                {
                    try{
                        if (moment(value, dataFormat, true).isValid())
                        {
                           var m =  moment(value, dataFormat, true);
                           return new Date(m.year(), m.month(), m.date(), m.hours(), m.minutes(), m.seconds(), m.milliseconds());   
                        }
                        else return value;
                    }
                    catch(err) {
                       return value;
                    }       
                }
            }
            
            function selectGroupData(data, group)
            {
                var ret = [];
                
                if (data.length > 0)
                {
                  for (var index = 0; index < data.length; index++)
                  {
                    if (data[index].group === group)
                    {
                      ret.push({x: parseDate(data[index].x),
                                y: data[index].y})     
                    }
                  }      
                }
                
                return ret;
            }
            
            function selectNotGroupData(data)
            {
                var ret = [];
                
                if (data.length > 0)
                {
                  for (var index = 0; index < data.length; index++)
                  {
                    if (!data[index].group)
                    {
                      ret.push({x:  parseDate(data[index].x),
                                y: data[index].y})      
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
                      dataRet.push({
                            //values - represents the array of {x,y} data points
                            values: selectGroupData(ret, groups[iGroup]), 
                            key: unescape(encodeURIComponent(groups[iGroup]))
                          });  
                    }
                    //valores que no estan en grupos
                   
                }
                else
                {
                  dataRet.push({
                      values: selectNotGroupData(ret), 
                      key: 'Data'
                   });   
                }
                                
                if ((!raw.hasLoadData) && raw.content
                    && raw.content.params
                    && raw.content.params.dimension 
                    && (raw.content.params.dimension.length > 0))
                {
                   model.setDimensionData(raw.content.params.dimension);
                   
                   //opciones chart
                   var iOption = 0;
                   chart.options().map(function (d){ 
                       d.value = raw.content.params.options[iOption];
                       iOption++;
                       return d.value;
                   });
                   
                   raw.hasLoadData = true;
                }
                
                
            }
                
            return dataRet;
	});
        
        
	// Drawing function
	// selection represents the d3 selection (svg)
	// data is not the original set of records
	// but the result of the model map function
	chart.draw(function (selection, data){
            
            $('#chart').empty();
            
            var width = w()
            ,   height = h()
            ,   xLabel = xL() || '' 
            ,   yLabel = yL() || '';
                        
               
            raw.content.params = {
                   chartId: chart.getId(),
                   chartType: 'simpleLineChart',
                   width: width,
                   height: height,
                   xLabel: xLabel,
                   yLabel: yLabel,
                   //parametros de optiones del modelo en orden
                   options: chart.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};   
            
            var svg = d3.select("#chart").append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
           
            /*These lines are all chart setup.  Pick and choose which chart features you want to utilize. */
            nv.addGraph(function() {
                var chart = nv.models.lineChart()
                            .margin({left: 100})  //Adjust chart margins to give the x-axis some breathing room.
                            .useInteractiveGuideline(true)  //We want nice looking tooltips and a guideline!
                            //.transitionDuration(350)  //how fast do you want the lines to transition?
                            .showLegend(true)       //Show the legend, allowing users to turn on/off line series.
                            .showYAxis(true)        //Show the y-axis
                            .showXAxis(true)        //Show the x-axis
                ;

                chart.xAxis     //Chart x-axis settings
                    .axisLabel(xLabel)
                    .tickFormat(function(d) { 
                        // http://www.d3noob.org/2012/12/formatting-date-time-on-d3js-graph.html
                        // https://github.com/mbostock/d3/wiki/Time-Formatting
                        // %x - date, as "%m/%d/%y".
                        return d3.time.format('%x')(new Date(d)); 
                    });
                    
                    //.tickFormat(d3.format(',r'));
                    //.tickFormat(d3.format('%Y-%m-%d'));
                    

                chart.yAxis     //Chart y-axis settings
                    .axisLabel(yLabel)
                    .tickFormat(d3.format('.02f'));

                /* Done setting the chart up? Time to render it!*/
                //var myData = sinAndCos();   //You need data...

                d3.select('#chart svg')    //Select the <svg> element you want to render the chart in.   
                    .datum(data)         //Populate the <svg> element with chart data...
                    .call(chart);          //Finally, render the chart!

                //Update the chart when window resizes.
                // nv.utils.windowResize(function() { chart.update() });
                return chart;
            }); 
	});
})();