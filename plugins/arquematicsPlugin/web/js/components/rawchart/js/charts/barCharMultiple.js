
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
  
  arquematics.docviews.barCharMultiple = function()
  {
  }
  
  arquematics.docviews.barCharMultiple.prototype = {
    show: function(data, domSelectString, lang, autoSize){

       var domNode = this.getDomObject(domSelectString) 
       , localization = {
          "stacked-es": "Apiladas",
          "grouped-es": "Agrupadas",
          "stacked-en": "Stacked",
          "grouped-en": "Grouped"
        }
       , showX = data.params.options[0]
       , showY = data.params.options[1]
       , reduceXTicks = data.params.options[2]
       , w =  data.params.options[3]
       , h = data.params.options[4]
       , m = data.params.options[5]
       , rotate = data.params.options[6];

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

           var chart = nv.models.multiBarChart()
                .margin({top: m.top, right: m.right, bottom: m.bottom, left: m.left})
                .reduceXTicks(reduceXTicks)   
                .showXAxis(showX)
                .showYAxis(showY)
                .rotateLabels(rotate)     
                .groupSpacing(0.5)    
            ;
            
            chart.controlLabels({
                "stacked": localization['stacked-' + lang],
                "grouped": localization['grouped-' + lang] })
            ;
            /*
            chart.legend.margin({top: 12, right: 0, bottom: 0, left: 0});
            chart.controls.margin({top: 12, right: 0, bottom: 0, left: 0});
           */
          
          /*
            chart.yAxis.tickPadding(25);
            chart.xAxis.tickPadding(25);
          */
           
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



