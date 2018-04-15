<?php use_helper('I18N','a','ar') ?>

<?php $mediaObj = isset($mediaObj) ? $sf_data->getRaw('mediaObj') : false; ?>
<?php $aPage = isset($aPage) ? $sf_data->getRaw('aPage') : false; ?>

<?php use_stylesheet("/arquematicsPlugin/css/normalize.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/bootstrap.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/bootstrap-responsive.css"); ?>
  
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome-ie7.css"); ?>
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/3dview.css"); ?>


<?php use_javascript("/arquematicsPlugin/js/3d/Three.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/3d/plane.js"); ?>


<?php use_javascript("/arquematicsPlugin/js/3d/thingiview.js"); ?>

<script type="text/javascript">
    var thingiview = null;
    var thingiurlbase = null;
    //var wireframeMode = true;
    //var rotation = true;
        
    thingiurlbase = "/arquematicsPlugin/js/3d";
    
    $(document).ready(function()
    {
        document.oncontextmenu = function () { // Use document as opposed to window for IE8 compatibility
            return false;
        };
        
        window.addEventListener('contextmenu', function (e) { // Not compatibile with IE < 9
            e.preventDefault();
        }, false);

        $('#cmd-top').on( "click", function(e) {
           e.preventDefault();
           thingiview.setCameraView('top');
        });
        
        $('#cmd-front').on( "click", function(e) {
           e.preventDefault();
           thingiview.setCameraView('front');
        });
        
        $('#cmd-side').on( "click", function(e) {
            e.preventDefault();  
           thingiview.setCameraView('side');
        });
        
        $('#cmd-diagonal').on( "click", function(e) {
            e.preventDefault();  
           thingiview.setCameraView('diagonal');
        });
        
         $('#cmd-wire').on( "click", function(e) {
            e.preventDefault(); 
            
           thingiview.toggleWireframe();
        });
    
        
  function has_workers(){
    return !!window.Worker;
  }

  function has_canvas(){
    var elem = document.createElement( 'canvas' );
    return !!(elem.getContext && elem.getContext('2d'));
  }

  function has_webgl(){
    return !!window.WebGLRenderingContext;
  }

  function show_unsupported_message(){
    $('unsupported_browser').show();
  }

  function show_warning_message(){
    $('webgl_warning').show();
  }

  function show_thingiview(){
    //$('.main').each(function(e){ e.hide(); });
    
    $('.main').hide();
    
    //var obj_color = getColor('obj_color', '#2c5dde');
    //var bg_color = getColor('bg_color', '#a1a1a1');
    var obj_color = '#2c5dde';
    var bg_color = '#a1a1a1';
    //$('.ui').each(function(e){ e.show(); });
    $('.ui').show();

    
    thingiview = new Thingiview("thingiview");
    thingiview.setBackgroundColor(bg_color);
    thingiview.setObjectColor(obj_color);
    
    thingiview.initScene();
    thingiview.loadSTL('<?php echo url_for("@user_resource?type=arWallUpload&name=".$mediaObj->getBaseName()."&format=".$mediaObj->getExtension()."&size=original") ?>'); // OK
    
    
  }


  


    /*
  function setTitle(){
    thing_id = urlParams['thing_id'];
    thing_name = urlParams['thing'];
    creator = urlParams['creator'];
    if((thing_id != null) && (thing_name != null) && (creator != null)){
      $('title').insert({'top': '<a title="View on Thingiverse" target="_top" href="' + getThingUrl() + '">' + thing_name + ' by ' + creator + '</a>'});
    }
  }*/

    /*
  function setWatermarkLink(){
    $('#watermark a')[0].href = getThingUrl();
  }*/

  function setSize(){
    
    var w = $(window).width();
    var h = $(window).height();
    
    //console.log(w);
    
    $('.main').width(w);
    $('.main').height(h);
    
    $('.warning_container').height(h);
    
  }

  /*
  function getColor(name, default_color)
  {
    if(urlParams[name] != null){
      var colorstr = urlParams[name];
      if(/[0-9A-Fa-f]{6}/.test(colorstr)){
        return '#' + colorstr;
      }
    }
    return default_color;
  }*/

  
  
        setSize();
        //setTitle();
        //setWatermarkLink();
        if(!has_workers() || !has_canvas()){
          show_unsupported_message();
        } else if (!has_webgl()){
          show_warning_message();
        } /* else if (urlParams['preview']){
          show_preview();
        } */ else {
          //show_preview();
          show_thingiview();
        }

/*
  Event.observe(window,'load',
      function() {
       
      });*/
        
    });
  
</script>

  <div id="unsupported_browser" class="main" style="display:none;">
    <div class="warning_container">
      <div class="warning">
        <p>Sorry, your browser is not supported by Thingiview at this time. Please upgrade to a supported version of one of the following browsers:</p>
        <ul>
          <li><a target="_top" href="http://www.google.com/chrome">Google Chrome 9+</a> (Recommended)</li>
          <li><a target="_top" href="http://www.khronos.org/webgl/wiki/Getting_a_WebGL_Implementation">Firefox 4.0+</a></li>
          <li><a target="_top" href="http://www.khronos.org/webgl/wiki/Getting_a_WebGL_Implementation">Safari Nightly (OS X 10.6+ only)</a></li>
        </ul>
      </div>
    </div>
  </div>

  <div id="webgl_warning" class="main" style="display:none;">
    <div class="warning_container">
      <div class="warning">
        <p><strong>WARNING:</strong> Your browser does not support <a target="_top" href="http://www.khronos.org/webgl/wiki/Main_Page">WebGL</a>. Thingiview works best with WebGL support, which is available in the following browsers:</p>
        <ul>
          <li><a target="_top" href="http://www.google.com/chrome">Google Chrome 9+</a> (Recommended)</li>
          <li><a target="_top" href="http://www.khronos.org/webgl/wiki/Getting_a_WebGL_Implementation">Firefox 4.0+</a></li>
          <li><a target="_top" href="http://www.khronos.org/webgl/wiki/Getting_a_WebGL_Implementation">Safari Nightly (OS X 10.6+ only)</a></li>
        </ul>
        <p>Or, you can <a href="javascript: show_thingiview();">try Thingiview at your own risk</a>. It may lock up your computer!</p>
      </div>
    </div>
  </div>


  <div id="thing_preview" class="main" style="display:none;" onclick="show_thingiview();">
    <div id="thing_preview_imgdiv">
      <img id="thing_preview_image" src=""/>
      <div style="position: absolute; top:0; left: 0; width: 100%; height: 100%; background-color: #000; opacity: 0.7;"></div>
    </div>
    <div style="background: #000000; position: absolute; left: 0px; bottom: 0px; width: 100%" onclick="show_thingiview();">
        <p style="margin: 20px; margin-left: 10px; font-weight:bold; font-size: 2em; color: #FFFFFF"><a style="color: #FFFFFF; text-decoration: none" href="javascript: show_thingiview()">Click to Play</a></p>
      </div>
  </div>
  
  <div class="thingiview ui main" id="thingiview" style="display:none;"></div>
  
  <div id="title" class="ui overlay" style="display:none">
  </div>
 
   <?php slot('global-head'); ?>
    <!-- Navbar -->
    <?php if ($sf_user->isAuthenticated()): ?>
    
    
    <div id="header" class="navbar navbar-fixed-top">
        
      <div  class="mmbar">
           
            <div id="icon-menu" class="search-content">
                
                <ul class="pull-left">
                    <li class="navi link">
                        <span class="back">&nbsp;</span>
                        <a id="back-url-id"  href="<?php echo $aPage->getSlug() ?>" class="back"><?php echo __('Back',array(),'profile') ?></a>    
                    </li>
                </ul>
                
                
                <?php /*
                <div id="controls" class="controls ui" style="display:none">
    <div id="camera_control">
      <span style="font-size: small">Camera Angles</span>
      <div id="camera_buttons">
        <img src="/arquematicsWorkflowPlugin/images/3d/cube-top-red.png" onclick="thingiview.setCameraView('top');" />
        <img src="/arquematicsWorkflowPlugin/images/3d/cube-front-green.png" onclick="thingiview.setCameraView('front');" />
        <img src="/arquematicsWorkflowPlugin/images/3d/cube-side-blue.png" onclick="thingiview.setCameraView('side');" />
        <img src="/arquematicsWorkflowPlugin/images/3d/cube-all-colored.png" onclick="thingiview.setCameraView('diagonal');" />
      </div>
    </div>
    <div id="wireframe_control">
      <label><input id="wireframe_checkbox" type="checkbox" onclick="toggleWireframe();" /> Wireframe</label>
    </div>
    <div id="rotation_control">
      <label><input id="rotation_checkbox" type="checkbox" onclick="toggleRotation();" /> Rotation</label>
    </div>
  </div>
                    */    ?>
                
                <ul id="menu-main" class="top-controls">
                    <li id="cmd-top" class="top" title="<?php echo __('Top', null, 'file-view') ?>" >
                        <span class="control-buttom top" id="top_icon"></span>
                        <span class="control-text"><?php echo __('Top', null, 'file-view') ?></span>
                    </li>
                    <li id="cmd-front" class="front" title="<?php echo __('Front', null, 'file-view') ?>">
                        <span class="control-buttom front" id="front_icon"></span>
                        <span class="control-text"><?php echo __('Front', null, 'file-view') ?></span>
                    </li>
                    <li id="cmd-side" class="side" title="<?php echo __('Side', null, 'file-view') ?>">
                        <span class="control-buttom side" id="side_icon"></span>
                        <span class="control-text"><?php echo __('Side', null, 'file-view') ?></span>
                    </li>
                    <li id="cmd-diagonal" class="diagonal" title="<?php echo __('Diagonal', null, 'file-view') ?>">
                        <span class="control-buttom " id="diagonal_icon"></span>
                        <span class="control-text"><?php echo __('Diagonal', null, 'file-view') ?></span>
                    </li>
                    <li id="cmd-wire" class="wire" title="<?php echo __('Wireframe', null, 'file-view') ?>">
                        <span class="control-buttom " id="wire_icon"></span>
                        <span class="control-text"><?php echo __('Wireframe', null, 'file-view') ?></span>
                    </li>
                    
                </ul>
                <ul id="menu-main" class="top-controls control-right">
                    <li id="cmd-upload" class="upload" title="<?php echo __('Download', null, 'file-view') ?>">
                        <a href="<?php echo url_for("@user_resource?type=arWallUpload&name=".$mediaObj->getBaseName()."&format=".$mediaObj->getExtension()."&size=original") ?>">
                            <i id="upload_icon" class="icon-upload-alt icon-large"></i>
                            <span class="control-text"><?php echo __('Download', null, 'file-view') ?></span>
                        </a>
                    </li>
                </ul>
                
               
            </div>
      </div>
       
    </div><!--/Navbar-->

<?php endif ?>
    
    
<?php end_slot(); ?>