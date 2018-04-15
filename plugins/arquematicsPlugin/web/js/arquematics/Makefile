# Set the source directory
srcdir = ./
rawchart = /var/www/vhosts/raf6.com/httpdocs/plugins/arquematicsPlugin/web/js/components/rawchart/js/

# Create the list of modules
coreModules =   ${srcdir}arquematics.js\
	    ${srcdir}arquematics.store.js\
	    ${srcdir}arquematics.codec.js\
	    ${srcdir}arquematics.mime.js\
	    ${srcdir}arquematics.utils.js\
	    ${srcdir}arquematics.ecc.js\
	    ${srcdir}arquematics.document.js\
	    ${rawchart}charts/barChar.js\
	    ${rawchart}charts/barCharMultiple.js\
	    ${rawchart}charts/histogram.js\
	    ${rawchart}charts/pieChart.js\
	
arquematics.core.js: ${coreModules}
	cat > $@ $^
