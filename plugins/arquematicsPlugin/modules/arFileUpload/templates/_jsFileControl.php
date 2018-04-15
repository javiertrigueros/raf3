<?php $uploads = isset($uploads) ? $sf_data->getRaw('uploads') : array(); ?>

<?php $uploadsGuiIds = isset($uploadsGuiIds) ? $sf_data->getRaw('uploadsGuiIds') : array(); ?>
<?php $userid = isset($userid) ? $sf_data->getRaw('userid') : null; ?>

<?php $hasContent = isset($hasContent) ? $sf_data->getRaw('hasContent') : false; ?>
<?php $showTool = isset($showTool) ? $sf_data->getRaw('showTool') : false; ?>

<script type="text/javascript">

 $(document).ready(function()
    {
        
       var item = $('#fileupload').fileupload({
            wait: false,
            sesionFiles: <?php echo json_encode($uploads, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>,
            sesionFilesGuiIds: <?php echo json_encode($uploadsGuiIds, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP); ?>,
            sessionFileDeleteURL: '<?php echo url_for('@wall_file_session_delete?user_id='.$userid.'&gui_id=') ?>',
            url: '<?php echo url_for('@wall_file_send') ?>',
            statusUrl: '<?php echo url_for('@wall_file_session') ?>', 
            maxNumberOfFilesError: '<?php echo __('Maximum number of files exceeded',array(),'wall') ?>',
            acceptFileTypesError: '<?php echo __('Filetype not allowed',array(),'wall') ?>',
            maxFileSizeError: '<?php echo __('File is too big',array(),'wall') ?>',
            minFileSizeError: '<?php echo __('File is too small',array(),'wall') ?>',
            error500: '<?php echo __('Error 500: Internal Server Error',array(),'wall') ?>',
            maxFileSize: <?php echo sfConfig::get('app_arquematics_plugin_max_file_size') ?>,
            acceptFileTypes: /.+$/i,
            autoUpload: true,
            sequentialUploads: true,
            gui_id: '#ar_wall_uploads_gui_id',
            //id del elemento que activa el tool
            tool_handler: '#arFileUpload',
            tool_container: '#file-control',
            tool_focus: '#wallMessage_message',
            has_content: <?php echo ($hasContent)?'true':'false' ?>,
            show_tool: <?php echo ($showTool)?'true':'false' ?>,
            
            // The add callback is invoked as soon as files are added to the fileupload
            // widget (via file input selection, drag & drop or add API call).
            // See the basic file upload widget for more information:
            
            add: function (e, data) {
                var that = $(this).data('fileupload'),
                    options = that.options,
                    files = data.files;
              
                var uniqueId = (new Date()).getTime();
                
                $(that.options.gui_id).val(uniqueId);
                
                if (!(typeof uniqueId == "string"))
                {
                  uniqueId = uniqueId.toString();
                  that.options.sesionFilesGuiIds.push(uniqueId);  
                }
                else
                {
                   that.options.sesionFilesGuiIds.push(uniqueId);   
                }
                
                     
                $(this).fileupload('process', data).done(function () {
                    that._adjustMaxNumberOfFiles(-files.length);
                    data.maxNumberOfFilesAdjusted = true;
                    data.files.valid = data.isValidated = that._validate(files);
                    data.files.gui_id = uniqueId;
                    data.context = that._renderUpload(files).data('data', data);
                    
                    options.filesContainer[
                        options.prependFiles ? 'prepend' : 'append'
                    ](data.context);
                    that._renderPreviews(files, data.context);
                    that._forceReflow(data.context);
                    that._transition(data.context).done(
                        function () {
                            if ((that._trigger('added', e, data) !== false) &&
                                    (options.autoUpload || data.autoUpload) &&
                                    data.autoUpload !== false && data.isValidated)
                            {       
                                data.jqXHR = data.submit();
                            }
                        }
                    );
                });
            },
            
            //para la descarga
            stop: function (e) {
                var that = $(this).data('fileupload');
                that._transition($(this).find('.fileupload-progress')).done(
                    function () {
                        $(this).find('.progress')
                            .attr('aria-valuenow', '0')
                            .find('.bar').css('width', '0%');
                        $(this).find('.progress-extended').html('&nbsp;');
                        that._trigger('stopped', e);
                        
                    }
                );
            },
            
            //ha enviado el archivo
            done: function (e, data) 
            {
             var that = $(this).data('fileupload');
             var template;
             var file = {};
             if (data.context) 
             {
                data.context.each(function (index) {
                   
                        var jsonData = data.result;
                        var file;
                        if ($.isPlainObject(jsonData) 
                            && (jsonData.status === 200))
                        {
                            file = jsonData.values.file; 
                           
                        }
                        else
                        {
                            file = {error: that.options.error500};
                            //file = {error: true}
                            that._adjustMaxNumberOfFiles(1);
                        }

                       
                        that._transition($(this)).done(
                            
                            function () {
                               
                                var node = $(this);
                                template = that._renderDownload([file])
                                    .replaceAll(node);
                                that._forceReflow(template);
                                that._transition(template).done(
                                    function () {
                                        data.context = $(this);
                                        that._trigger('completed', e, data);
                                    }
                                );
                            }
                        );
                    });
             }
            
             
            },
            //borrar archivo de session
            destroy: function (e, data) {
                var that = $(this).data('fileupload');
                var url = data.context.context.dataset.url;
                var guiId = data.context.context.dataset.gui;
                
                if (url)
                {
                    $.ajax({
                        type: "POST",
                        url: url,
                        datatype: "json",
                        //data: dataString,
                        cache: false,
                        success: function(dataJSON)
                        {
                            if (dataJSON.status === 200)
                            {
                              //borra el elemento de la lista
                              var index = $.inArray(guiId, that.options.sesionFilesGuiIds);
                              if(index != -1)
                              {
                                that.options.sesionFilesGuiIds.splice(index, 1);
                              }
                              
                              
                              that._transition(data.context).done(
                                function () {
                                    $(this).remove();
                                   
                                    that._trigger('destroyed', e, data);
                                }
                              );
                            }
                            else
                            {
                                console.log('error file delete');
                            }
                            
                        }
                    });
                }
              
               
            },
            //fallo carga de fichero
            fail: function (e, data) {
                var that = $(this).data('fileupload');
                var template;
                if (data.maxNumberOfFilesAdjusted) {
                    that._adjustMaxNumberOfFiles(1);
                }
                if (data.context) {
                    data.context.each(function (index) {
                        if (data.errorThrown !== 'abort') {
                            var file = data.files[index];
                          
                            file.error = 'Internal Server Error';
                            
                            that._transition($(this)).done(
                                function () {
                                    var node = $(this);
                                    template = that._renderDownload([file])
                                        .replaceAll(node);
                                    that._forceReflow(template);
                                    that._transition(template).done(
                                        function () {
                                            data.context = $(this);
                                            that._trigger('failed', e, data);
                                        }
                                    );
                                }
                            );
                        } else {
                            that._transition($(this)).done(
                                function () {
                                    $(this).remove();
                                    that._trigger('failed', e, data);
                                }
                            );
                        }
                    });
                } else if (data.errorThrown !== 'abort') {
                    data.context = that._renderUpload(data.files)
                        .appendTo(that.options.filesContainer)
                        .data('data', data);
                    that._forceReflow(data.context);
                    that._transition(data.context).done(
                        function () {
                            data.context = $(this);
                            that._trigger('failed', e, data);
                        }
                    );
                } else {
                    that._trigger('failed', e, data);
                }
            },
            
            //proceso de archivo
            process: [
                {
                    action: 'load',
                    fileTypes: /^image\/(gif|jpeg|png)$/,
                    maxFileSize: 20000000 // 20MB
                },
                {
                    action: 'resize',
                    maxWidth: 1440,
                    maxHeight: 900
                },
                {
                    action: 'save'
                }
            ]
        });
        
        //var wall = $('#arWall').data('wall');
        
        arquematics.wall.subscribeTool(item.data('fileupload'));
       
});

</script>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) {  %}
    <tr class="template-upload fade" id="{%=o.files.gui_id %}">
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="preview"><span class="fade"></span></td>
        
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <span><?php echo __('Start',array(),'wall') ?></span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning" data-gui="{%=o.files.gui_id%}">           
                <span><?php echo __('Cancel',array(),'wall') ?></span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade" id="{%=file.gui_id%}">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important"><?php echo __('Error',array(),'wall') ?></span> {%=file.error%}</td>
        {% } else { %}
            <td class="name"> 
                <a href="{%=file.url%}"  title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="preview">
            {% if (file.is_image) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}
            </td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-gui="{%=file.gui_id%}" data-type="{%=file.type%}" data-url="{%=file.delete_url%}">       
                <span><?php echo __('Delete',array(),'wall') ?></span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>