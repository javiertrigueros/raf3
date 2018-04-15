/**
 * @package: arquematicsPlugin
 * @version: 0.1
 * @Autor: Arquematics 2010 
 *         by Javier Trigueros Martínez de los Huertos
 *         
 * 
 */
(function($, Bloodhound) {

$.widget( "arquematics.search", {
	options: {
            //fomulario busqueda
            form:                   '#form-search',
            send_button:            '.cmd-search',
            input_control:          '#search_search',
            input_control_page:     '#search_page',
            
            user_template:          '#user-search-template',
            
            autocomplete_url:       ''
           
        },
        _create: function() 
        {           
           this._initEventHandlers();
	},
        
        _initEventHandlers: function () 
        {
           var options = this.options
           , $form = $(options.form)
           , bloodhound = new Bloodhound({
                                datumTokenizer: function (datum) {
                                    return Bloodhound.tokenizers.whitespace(datum.value);
                                },
                                queryTokenizer: Bloodhound.tokenizers.whitespace,
                                limit: 20,
                                remote: {
                                    url: $form.attr('action'),
                                    prepare: function (query, settings)
                                    {
                                        settings.type = "POST";
                                        settings.data = $form.find('input, select, textarea').serialize();

                                        return settings;
                                    }
                                }});

            // Initialize 
            bloodhound.initialize();
        
            $(options.input_control).typeahead(null, {
                displayKey: 'value',
                source: bloodhound.ttAdapter(),
                    templates: {
                        suggestion: function(el){
                            return $('#user-search-template').tmpl({
                                first_last: el.first_last,
                                id: el.id,
                                image: el.image,
                                profile: el.profile,
                                wall: el.wall
                            });
                        }
                    }
            });
        
            $(options.input_control).bind('typeahead:select', function(e, suggestion) {
            
                e.preventDefault();
                e.stopPropagation();
           
                $(this).typeahead('val',suggestion.first_last);
            
                $('body').trigger('resetWallContent',  suggestion.wall); 
            
                $(options.input_control).focus();
            
                return false;
            });

            $(options.input_control).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                
                if (keycode == 13) {
                  $(".tt-suggestion:first-child").trigger('click');
                  $(this).typeahead('close');
                }
            });
            
            $(options.send_button).bind('click', function (e)
            {
                 $(".tt-suggestion:first-child").trigger('click');
                 $(options.input_control).typeahead('close');
            });
        }
});

}(jQuery,  Bloodhound));