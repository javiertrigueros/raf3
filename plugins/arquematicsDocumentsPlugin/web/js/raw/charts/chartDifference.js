(function(){
   // http://bl.ocks.org/mbostock/3894205
   // 
	// A simple scatterplot for APIs demo

	// The Model

	var model = raw.model();

	// X axis dimension
	// Adding a title to be displayed in the UI
 	// and limiting the type of data to Numbers only
	var x = model.dimension() 
		.title('X Axis')
                .types(Number, Date)
                .required(1);

	// Y axis dimension
	// Same as X
	var y = model.dimension() 
		.title('Y Axis')
		.types(Number)
                .required(1)
                .multiple(true);
        
        model.map(function (data){
		return data.map(function (d){
			return {
				x : +x(d),
				y : +y(d)
			};
		});
	});
       
	
	// The Chart

	var chart = raw.chart()
		.title("Chart Difference")
		.description("Chart Difference")
                .category('Distributions')
		.model(model);

	// Some options we want to expose to the users
	// For each of them a GUI component will be created
	// Options can be use within the Draw function
	// by simply calling them (i.e. witdh())
	// the current value of the options will be returned

	// Width
	var width = chart.number()
		.title('Width')
		.defaultValue(900);

	// Height
	var height = chart.number()
		.title('Height')
		.defaultValue(600);

	// A simple margin
	var margin = chart.number()
		.title('margin')
		.defaultValue(10);

	// Drawing function
	// selection represents the d3 selection (svg)
	// data is not the original set of records
	// but the result of the model map function
	chart.draw(function (selection, data){
            var height = height();
            var width = width();
	    // svg size
            selection
		.attr("width", width)
		.attr("height", height);
            
             /*var width   = width() - margin(),
                height  = height() - margin();*/

		var testdata = [
                    {key: "One", y: 5},
                    {key: "Two", y: 2},
                    {key: "Three", y: 9},
                    {key: "Four", y: 7},
                    {key: "Five", y: 4},
                    {key: "Six", y: 3},
                    {key: "Seven", y: 0.5}
            ];
            
           
            
            nv.addGraph(function() {
                var chart = nv.models.pieChart()
                            .x(function(d) { return d.key })
                            .y(function(d) { return d.y })
                            .width(width)
                            .height(height);

                d3.select("#chart svg")
                    .datum(testdata)
                    .transition()
                    .duration(1200)
                    .attr('width', width)
                    .attr('height', height)
                    .call(chart);

                return chart;
            });
            
            
            
    
    function sinData() {
        var sin = [];

        for (var i = 0; i < 100; i++) {
            sin.push({x: i, y: Math.sin(i/10) * Math.random() * 100});
        }

        return [{
            values: sin,
            key: "Sine Wave",
            color: "#ff7f0e"
        }];
    }

    function sinAndCos() {
        var sin = [],
            cos = [];

        for (var i = 0; i < 100; i++) {
            sin.push({x: i, y: Math.sin(i/10)});
            cos.push({x: i, y: .5 * Math.cos(i/10)});
        }

        return [
            {
                values: sin,
                key: "Sine Wave",
                color: "#ff7f0e"
            },
            {
                values: cos,
                key: "Cosine Wave",
                color: "#2ca02c"
            }
        ];
    }
                
             

	});
})();