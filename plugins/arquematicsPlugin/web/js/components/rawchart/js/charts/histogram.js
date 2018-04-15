
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
  
  arquematics.docviews.histogram = function()
  {
  }
  
  arquematics.docviews.histogram.prototype = {
    show: function(data, domSelectString, lang, autoSize){
       var domNode = this.getDomObject(domSelectString) 
       , w =  data.params.options[0]
       , h = data.params.options[1]
       , m = data.params.options[2]
       , nClases = data.params.options[3];

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
            
          var chart = nv.models.linePlusBarChart()
                    .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                    .x(function(d,i) { return i })
                    .y(function(d,i) {return d[1] })
                    .focusEnable(false)
           ;
           
          chart.xAxis.tickFormat(function(d) {
            return (typeof(values[0].values[d]) != "undefined")?
                values[0].values[d][0]
                : '';
          });
           
          chart.xAxis.ticks(values[0].values.length)

          chart.y1Axis.tickFormat(d3.format(',f'));

          chart.y2Axis
            .tickFormat(function(d) { return d3.format('%')(d) });
            
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



