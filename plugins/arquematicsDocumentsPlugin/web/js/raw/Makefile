# Set the source directory
srcdir = ./

# Create the list of modules
coreModules =   ${srcdir}bower_components/bootstrap/dist/js/bootstrap.js\
	    ${srcdir}bower_components/bootstrap-colorpicker/js/bootstrap-colorpicker.js\
	    ${srcdir}../../../../arquematicsPlugin/web/js/vendor/bootstrap/plugins/bootstrap-modal-carousel/bootstrap-modal-carousel.js\
	    ${srcdir}bower_components/moment/min/moment-with-locales.js\
	    ${srcdir}bower_components/d3/d3.js\
	    ${srcdir}bower_components/d3-time/d3-time.v1.min.js\
	    ${srcdir}bower_components/d3-time-format/d3-time-format.v2.min.js\
	    ${srcdir}bower_components/d3-plugins/sankey/sankey.js\
	    ${srcdir}bower_components/d3-plugins/hexbin/hexbin.js\
	    ${srcdir}../vendor/nvd3/build/nv.d3.js\
	    ${srcdir}bower_components/codemirror/lib/codemirror.js\
	    ${srcdir}bower_components/codemirror/addon/display/placeholder.js\
	    ${srcdir}bower_components/canvas-toBlob.js/canvas-toBlob.js\
	    ${srcdir}bower_components/FileSaver/FileSaver.js\
	    ${srcdir}bower_components/zeroclipboard/ZeroClipboard.js\
	    ${srcdir}bower_components/jstat/dist/jstat.js\
	    ${srcdir}bower_components/angular/angular.js\
	    ${srcdir}bower_components/angular-route/angular-route.js\
	    ${srcdir}bower_components/angular-animate/angular-animate.js\
	    ${srcdir}bower_components/angular-sanitize/angular-sanitize.js\
	    ${srcdir}bower_components/angular-strap/dist/angular-strap.js\
	    ${srcdir}bower_components/angular-strap/dist/angular-strap.tpl.js\
	    ${srcdir}bower_components/angular-ui/build/angular-ui.js\
	    ${srcdir}bower_components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.js\
	    ${srcdir}bower_components/angular-gettext/dist/angular-gettext.js\
	    ${srcdir}bower_components/angular-moment/angular-moment.js\
	    ${srcdir}lib/raw.js\
	    ${srcdir}js/app.js\
	    ${srcdir}js/services.js\
	    ${srcdir}js/filters.js\
	    ${srcdir}js/directives.js\
	    ${srcdir}js/controlerEdit.js\
	    ${srcdir}js/controlerView.js\
	    #${srcdir}charts/histogram.js\
	    #${srcdir}charts/simpleLineChart.js\
	    #${srcdir}charts/treemap.js\
	    #${srcdir}charts/streamgraph.js\
	    #${srcdir}charts/scatterPlot.js\
	    #${srcdir}charts/packing.js\
	    #${srcdir}charts/clusterDendrogram.js\
	    #${srcdir}charts/voronoi.js\
	    #${srcdir}charts/delaunay.js\
	    #${srcdir}charts/alluvial.js\
	    #${srcdir}charts/clusterForce.js\
	    #${srcdir}charts/convexHull.js\
	    #${srcdir}charts/hexagonalBinning.js\
	    #${srcdir}charts/reingoldTilford.js\
	    #${srcdir}charts/parallelCoordinates.js\
	    #${srcdir}charts/circularDendrogram.js\
	    #${srcdir}charts/smallMultiplesArea.js\
	    #${srcdir}charts/bumpChart.js\
	    #${srcdir}charts/distributionChar.js \
	    #${srcdir}charts/barChar.js\
	    #${srcdir}charts/barCharMultiple.js\
	    #${srcdir}charts/pieChart.js\

all: clean build
 
clean:
	rm -f arquematics.raw.min.js arquematics.raw.js

build:
	cat ${coreModules} > arquematics.raw.js
	#java -jar yuicompressor-2.4.8.jar arquematics.raw.js -v --type js -o arquematics.raw.min.js --charset utf-8 
	uglifyjs --compress --mangle -- arquematics.raw.js  > arquematics.raw.min.js
