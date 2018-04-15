(function(raw, d3, nv, jStat){
    
    raw.models.set('distributionChar', function(){
	var model = raw.model();
        
	var chartD3 = raw.chart()
		.title("Normal (Gauss)")
		.description("Presents grouped data with rectangular bars with lengths proportional to the values that they represent.")
                .category('Distributions')
                .nameFunc('distributionChar')
                .thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/barchar.png")
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
        
        var media = chartD3.number()
		.title('Mean')
		.defaultValue(0);
                
        var standardDev = chartD3.number()
		.title('Standard deviation')
		.defaultValue(0);
                
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
              , countObj = {"type": "bar", "yAxis": 1,  'key': keyName, color: '#ccf', values: []}
              , pdfObj = {"type": "line", "yAxis": 1,  'key': 'Gauss pdf', color: 'red', values: []}
              , cdfObj = {"type": "line", "yAxis": 1,  'key': 'Gauss cdf', color: 'steelblue', values: []}
              //, studenttObj = {"type": "line", "yAxis": 1,  'key': 'Studentt', color: '#333', values: []}
              , initClass = 0
              , endClass = 0
              , i = 0
              , min = Math.floor(d3.min(data))
              , max = Math.ceil(d3.max(data))
              , mean = parseFloat(media())
              , stdev = parseFloat(standardDev())
              ;
            
            if (mean == 0)
            {
              mean = jstat.mean();
              media.value = mean;
            }
            else
            {
              mean = media(); 
            }
            
            if (stdev == 0)
            {
              stdev = jstat.stdev(); 
              standardDev.value = stdev; 
            }
            else
            {
               stdev = standardDev();
            }
            
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
            
            var  binSize = max / endClass;
            for(i = initClass;  (i <= endClass) && (i < binData.length); i++)
            {
                countObj.values.push([binData[i].x,binData[i].y]);
                cdfObj.values.push([binData[i].x, binSize * data.length * jstat.normal(mean, stdev).cdf(binData[i].x)]);      
                pdfObj.values.push([binData[i].x, binSize * data.length * jstat.normal(mean, stdev).pdf(binData[i].x)]);
            }
            
            var histogramDataIndex = initClass;
            for(i = binData[initClass].x;  (i <= binData[binData.length -1].x); i +=0.01)
            {
                if (i > binData[histogramDataIndex].x)
                {
                  histogramDataIndex++;      
                }
                
                countObj.values.push([i,binData[histogramDataIndex].y]);
                cdfObj.values.push([i, binSize * data.length * jstat.normal(mean, stdev).cdf(i)]);      
                pdfObj.values.push([i, binSize * data.length * jstat.normal(mean, stdev).pdf(i)]);
            }
            
            countObj.values = countObj.values.sort(function(x, y) {
                return x[0] - y[0];
            });
                 
            cdfObj.values = cdfObj.values.sort(function(x, y) {
                return x[0] - y[0];
            });
            
            pdfObj.values = pdfObj.values.sort(function(x, y) {
                return x[0] - y[0];
            });
            
            convertedData.push(countObj);
            convertedData.push(pdfObj);
            convertedData.push(cdfObj);

            convertedData = convertedData.map(function(series) {
                series.values = series.values.map(function(d) {
                    return {
                        x: d[0],
                        y: d[1]
                    }});
          
                return series;
            });
            
            return convertedData;
        }
        
        model.map(function (data){
            
            var ret = data.map(function (d){
			return +xD(d);
		});
           
            ret = processData(ret);
           
           
            
            return ret;
	});
        
         chartD3.draw(function (selection, data){
    
            $('#chart').empty();
     
            //define margins for chart, histogram bin size, and the x scale for the bins
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
                   chartType: 'distributionChar',
                   options: chartD3.options().map(function (d){ return d.value }),
                   dimension: model.dimensionData(),
                   data: data};

            var c = nv.addGraph(function() {
            var chart = nv.models.multiChart()
                    .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                    .height(h)
                    .width(w)
                    .interpolate("cardinal")
                    .useInteractiveGuideline(true)
            ;

            d3.select('#chart svg')
                .datum(data)
                .transition()
                .duration(500)
                .call(chart)
            ;
    
    /*
        var date_now_xposition = chart.xAxis.scale()(getInfo.mean);

        // When nvd3 draw chart it append a rectangle of the inner
        // size of our chart (without label axis or title)
        // let's get it to know height and width of our rectangle
        var rect_back = document.getElementsByClassName("nvd3")[0].firstChild.firstChild;

        // draw a background rectangle to indicate the future
        // Here we will append new rectangle to ".nv-groups"
        // this allow to avoid to break nvd3 hover fearure on chart
        d3.select('.nv-groups').append("rect")
          .attr("x", date_now_xposition) // start rectangle on the good position
          .attr("y", 0) // no vertical translate
          .attr("width", rect_back.getAttribute('width') - date_now_xposition) // correct size
          .attr("height", rect_back.getAttribute('height')) // full height
          .attr("fill", "rgba(66,139,202, 0.2)"); // transparency color to see grid
*/
                chart.update();
                nv.utils.windowResize(chart.update);
                return chart;
            });
   
            return c;
        });
        
        return model;
    });
    
    raw.models.distributionChar = raw.models.get('distributionChar');

    raw.models.distributionChar();
    
})(raw, d3, nv, jStat || window.jStat || this.jStat  );