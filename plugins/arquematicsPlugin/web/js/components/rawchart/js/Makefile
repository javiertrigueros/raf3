# Set the source directory
srcdir = /var/www/vhosts/raf6.com/httpdocs/plugins/arquematicsDocumentsPlugin/web/js/raw/
srcdirview = /var/www/vhosts/raf6.com/httpdocs/plugins/arquematicsPlugin/web/js/components/rawchart/js/
srcarquematics = /var/www/vhosts/raf6.com/httpdocs/plugins/arquematicsPlugin/web/js/arquematics/

# Create the list of modules
coreModules = ${srcdir}bower_components/d3/d3.js\
	    ${srcdir}bower_components/d3-time/d3-time.v1.min.js\
	    ${srcdir}bower_components/d3-time-format/d3-time-format.v2.min.js\
	    ${srcdir}bower_components/d3-plugins/sankey/sankey.js\
	    ${srcdir}bower_components/d3-plugins/hexbin/hexbin.js\
	    ${srcdir}../vendor/nvd3/build/nv.d3.js\
	    ${srcdir}../vendor/nvd3/build/nv.d3.js\
	    ${srcarquematics}arquematics.js\
	    ${srcarquematics}arquematics.document.js\
	    ${srcdirview}charts/barChar.js\
	    ${srcdirview}charts/barCharMultiple.js\
	    ${srcdirview}charts/histogram.js\
	    ${srcdirview}charts/pieChart.js\
	
all: clean build
 
clean:
	rm -f arquematics.raw.min.js arquematics.raw.js

build:
	cat ${coreModules} > arquematics.raw.js
	uglifyjs --compress --mangle -- arquematics.raw.js  > arquematics.raw.min.js
