(function(){
// The Model
/*
source, target, value
Agricultural Energy Use,Carbon Dioxide,1.4
Agriculture,Agriculture Soils,5.2
Agriculture,Livestock and Manure,5.4
Agriculture,Other Agriculture,1.7
Agriculture,Rice Cultivation,1.5
Agriculture Soils,Nitrous Oxide,5.2
Air,Carbon Dioxide,1.7
Aluminium Non-Ferrous Metals,Carbon Dioxide,1.0
Aluminium Non-Ferrous Metals,HFCs - PFCs,0.2
Cement,Carbon Dioxide,5.0
Chemicals,Carbon Dioxide,3.4
Chemicals,HFCs - PFCs,0.5
Chemicals,Nitrous Oxide,0.2
Coal Mining,Carbon Dioxide,0.1
Coal Mining,Methane,1.2
Commercial Buildings,Carbon Dioxide,6.3
Deforestation,Carbon Dioxide,10.9
Electricity and heat,Agricultural Energy Use,0.4
Electricity and heat,Aluminium Non-Ferrous Metals,0.4
Electricity and heat,Cement,0.3
Electricity and heat,Chemicals,1.3
Electricity and heat,Commercial Buildings,5.0
Electricity and heat,Food and Tobacco,0.5
Electricity and heat,Iron and Steel,1.0
Electricity and heat,Machinery,1.0
Electricity and heat,Oil and Gas Processing,0.4
Electricity and heat,Other Industry,2.7
Electricity and heat,Pulp - Paper and Printing,0.6
Electricity and heat,Residential Buildings,5.2
Electricity and heat,T and D Losses,2.2
Electricity and heat,Unallocated Fuel Combustion,2.0
Energy,Electricity and heat,24.9
Energy,Fugitive Emissions,4.0
Energy,Industry,14.7
Energy,Other Fuel Combustion,8.6
Energy,Transportation,14.3
Food and Tobacco,Carbon Dioxide,1.0
Fugitive Emissions,Coal Mining,1.3
Fugitive Emissions,Oil and Gas Processing,3.2
Harvest Management,Carbon Dioxide,1.3
Industrial Processes,Aluminium Non-Ferrous Metals,0.4
Industrial Processes,Cement,2.8
*/
	var graph = raw.models.graph();
        
        graph.clear();
        
        var chart = raw.chart()
		.title('Alluvial Diagram')
		.description("Alluvial diagrams allow to represent flows and to see correlations between categorical dimensions, visually linking to the number of elements sharing the same categories. It is useful to see the evolution of cluster (such as the number of people belonging to a specific group). It can also be used to represent bipartite graphs, using each node group as dimensions.")
		.thumbnail("/arquematicsDocumentsPlugin/js/raw/imgs/alluvial.png")
		.category("Correlations")
		.model(graph);

	var width = chart.number()
		.title("Width")
		.defaultValue(1000)
		.fitToWidth(true);

	var height = chart.number()
		.title("Height")
		.defaultValue(500);

	var nodeWidth = chart.number()
		.title("Node Width")
		.defaultValue(5);

	var sortBy = chart.list()
                        .title("Sort by")
                        .values(['size','name','automatic'])
                        .defaultValue('size');

	var colors = chart.color()
		.title("Color scale");
        
        //console.log(raw.content.params);
        /*
        graph.map(function (data){
            if ((!raw.hasLoadData) && raw.content
                    && raw.content.params
                    && raw.content.params.dimension 
                    && (raw.content.params.dimension.length > 0))
                {
                   graph.setDimensionData(raw.content.params.dimension);
                   
                   //opciones chart
                   var iOption = 0;
                   chart.options().map(function (d){ 
                       d.value = raw.content.params.options[iOption];
                       iOption++;
                       return d.value;
                   }),
                   
                   raw.hasLoadData = true;
                }
        });*/
    
	chart.draw(function (selection, data){
                //$('#chart').empty();

		var formatNumber = d3.format(",.0f"),
		    format = function(d) { return formatNumber(d); };

		var g = selection
                        .attr("width", +width() )
                        .attr("height", +height() + 20 )
                        .append("g")
                        .attr("transform", "translate(" + 0 + "," + 10 + ")");

               
		// Calculating the best nodePadding

		var nested = d3.nest()
                                .key(function (d){ return d.group; })
                                .rollup(function (d){ return d.length; })
                                .entries(data.nodes)

                var maxNodes = d3.max(nested, function (d){ return d.values; });

		var sankey = d3.sankey()
		    .nodeWidth(+nodeWidth())
		    .nodePadding(d3.min([10,(height()-maxNodes)/maxNodes]))
		    .size([+width(), +height()]);
                    
               /*
               var width = +width()
                , height = +height()
                , nodeWidth = d3.sankey().nodeWidth();
                */
               
             
        
               raw.content.params = {
                   chartId: chart.getId(),
                   chartType: 'alluvial',
                  // width: width,
                  // height: height,
                  // nodeWidth: nodeWidth,
                   //parametros de optiones del modelo en orden
                   options: chart.options().map(function (d){ return d.value }),
                   dimension: graph.dimensionData(),
                   data: data};

		var path = sankey.link(),
			nodes = data.nodes,
			links = data.links;

		sankey
                    .nodes(nodes)
                    .links(links)
                    .layout(32);

                // Re-sorting nodes

                nested = d3.nest()
                    .key(function(d){ return d.group; })
                    .map(nodes);

	    d3.values(nested)
	    	.forEach(function (d){
		    	var y = ( height() - d3.sum(d,function(n){ return n.dy+sankey.nodePadding();}) ) / 2 + sankey.nodePadding()/2;
		    	d.sort(function (a,b){ 
		    		if (sortBy() == "automatic") return b.y - a.y;
		    		if (sortBy() == "size") return b.dy - a.dy;
		    		if (sortBy() == "name") return a.name < b.name ? -1 : a.name > b.name ? 1 : 0;		
		    	});
                        
		    	d.forEach(function (node){
		    		node.y = y;
		    		y += node.dy +sankey.nodePadding();
		    	});
		    });

                // Resorting links

		d3.values(nested).forEach(function (d){

                    d.forEach(function (node){

	    		var ly = 0;
	    		node.sourceLinks
		    		.sort(function (a,b){
		    			return a.target.y - b.target.y;
		    		})
		    		.forEach(function (link){
		    			link.sy = ly;
		    			ly += link.dy;
		    		});
		    	
		    	ly = 0;

		    	node.targetLinks
		    		.sort(function(a,b){ 
		    			return a.source.y - b.source.y;
		    		})
		    		.forEach(function (link){
		    			link.ty = ly;
		    			ly += link.dy;
		    		})
			});
		});
	   
	 	colors.domain(links, function (d){ return d.source.name; });

		var link = g.append("g").selectAll(".link")
	    	.data(links)
	   		.enter().append("path")
			    .attr("class", "link")
			    .attr("d", path )
			    .style("stroke-width", function(d) { return Math.max(1, d.dy); })
			    .style("fill","none")
			    .style("stroke", function (d){ return colors()(d.source.name); })
			    .style("stroke-opacity",".4")
			    .sort(function(a, b) { return b.dy - a.dy; });

		var node = g.append("g").selectAll(".node")
                            .data(nodes)
                            .enter().append("g")
                            .attr("class", "node")
                            .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

		node.append("rect")
		    .attr("height", function(d) { return d.dy; })
		    .attr("width", sankey.nodeWidth())
		    .style("fill", function (d) { return d.sourceLinks.length ? colors(d.name) : "#666"; })
		    .append("title")
		    .text(function(d) { return d.name + "\n" + format(d.value); });

		node.append("text")
		    .attr("x", -6)
	      	.attr("y", function (d) { return d.dy / 2; })
	      	.attr("dy", ".35em")
	      	.attr("text-anchor", "end")
	      	.attr("transform", null)
			    .text(function(d) { return d.name; })
			    .style("font-size","11px")
                            .style("font-family","Arial, Helvetica")
			    .style("pointer-events","none")
			    .filter(function(d) { return d.x < +width() / 2; })
			    .attr("x", 6 + sankey.nodeWidth())
                            .attr("text-anchor", "start");
          
          return chart;
    });
})();