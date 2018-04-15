
module.exports = function(grunt) {
 

    grunt.initConfig({
	pkg: grunt.file.readJSON('package.json'),
        
        nggettext_compile: {
            all: {
                files: [
                    {
                        expand: true,
                        src: 'po/*.po',
                        dest: 'i18n/',
                        ext: '.js'
                    }
                ]
            }
        },
        nggettext_extract: {
            pot: {
                files: {
                    'po/template.pot': ['partials/*.html']
                }
            },
        },
    });
    
    grunt.loadNpmTasks('grunt-angular-gettext');
    
    grunt.registerTask('default', function() { // 4
      grunt.log.writeln('Hello, from the default grunt task!'); // 5
    });
};

