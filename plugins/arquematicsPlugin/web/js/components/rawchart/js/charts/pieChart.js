
/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 *  depende de:
 *  
 */

/**
 * 
 * @param {type} $
 * @param {type} arquematics
 */
var arquematics =  (function ($, d3, nv, window, arquematics) {
  
  arquematics.docviews.pieChart = function()
  {
  }
  
  arquematics.docviews.pieChart.prototype = {
    show: function(data, domSelectString, lang, autoSize){
       
       var domNode = this.getDomObject(domSelectString) 
       , w =  data.params.options[0]
       , h = data.params.options[1]
       , m = data.params.options[2];

       if (autoSize || false)
       {
          var proportionalSize = this.getProportionalResize(w, h, $(domNode).width(), h + h / 4);
       
          w = proportionalSize.width;
          h = proportionalSize.height;
       }

       var svg = d3.select(domNode)
                .append("svg")
                .attr("width", w)
                .attr("height", h)
                .append("g")
                .attr("transform", "translate(" + w / 2 + "," + h / 2 + ")")
        , values = data.params.data
        ;

  

        /*
        $(domNode).width()

        $(domNode).height()
        */
        
           
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
                chart.legend.margin({top: 10, right: 0, bottom: 0, left: 0});
                

                d3.select($(domNode).children().first().get(0))
                        .attr("width", w)
                        .attr("height", h)
                        .datum(values)
                        .transition()
                        .duration(350)
                        .call(chart);
   
                    
               return chart;
            });
            
            return c;
    }
  };
  
  return  arquematics;
}(jQuery, d3, nv, window, arquematics || {} ));