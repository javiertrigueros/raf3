
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
var arquematics =  (function ($, d3, nv, arquematics) {
  
  arquematics.docviews.barChar = function()
  {
  }
  
  arquematics.docviews.barChar.prototype = {
    show: function(data, domSelectString, lang, autoSize){

       var domNode = this.getDomObject(domSelectString) 
       , showX = data.params.options[0]
       , showY = data.params.options[1]
       , w =  data.params.options[2]
       , h = data.params.options[3]
       , m = data.params.options[4];
       
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
        
        nv.addGraph(function() {
            
          var chart = nv.models.discreteBarChart()
                    .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                    .x(function(d) { 
                        return d.label })    //Specify the data accessors.
                    .y(function(d) { 
                        return d.value })
                    .showXAxis(showX)
                    .showYAxis(showY)
                    .staggerLabels(true)
           ;
           
           chart.yAxis.tickFormat(function(d) {
                return (d % 1 != 0)? d: d3.format(",d")(d);
           });
        
           

          d3.select($(domNode).children().first().get(0))
            .attr("width", w)
            .attr("height", h)
            .datum(values)
            .transition()
            .duration(0)
            .call(chart);

          nv.utils.windowResize(chart.update);
          
          return chart;
        });
        
        
    }
  };
  
  return  arquematics;
}(jQuery, d3, nv, arquematics || {} ));



