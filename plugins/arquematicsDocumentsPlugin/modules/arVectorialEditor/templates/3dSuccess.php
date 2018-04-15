<?php use_helper('I18N','a','ar') ?>
 
<html><head>
<title>Arquematics</title>

<?php use_javascript("/arquematicsWorkflowPlugin/js/3d/prototype.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/3d/Three.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/3d/plane.js"); ?>
<?php use_javascript("/arquematicsWorkflowPlugin/js/3d/thingiview.js"); ?>

<?php a_include_javascripts() ?>

<script>
  var thingiview = null;
  var thingiurlbase = null;
  var wireframeMode = false;
  var rotation = true;

  var urlParams = {};
  (function () {
      var e,
          a = /\+/g,  // Regex for replacing addition symbol with a space
          r = /([^&=]+)=?([^&]*)/g,
          d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
          q = window.location.search.substring(1);

      while (e = r.exec(q))
         urlParams[d(e[1])] = d(e[2]);
  })();

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
    $$('.main').each(function(e){ e.hide(); });
    var obj_color = getColor('obj_color', '#2c5dde');
    var bg_color = getColor('bg_color', '#a1a1a1');
    $$('.ui').each(function(e){ e.show(); });
    //thingiurlbase = "/thingiview";
    
    thingiurlbase = "/arquematicsWorkflowPlugin/js/3d";
    
    thingiview = new Thingiview("thingiview");
    thingiview.setBackgroundColor(bg_color);
    thingiview.setObjectColor(obj_color);
    thingiview.onSetRotation(function(on_off){
      var cbx = $('rotation_checkbox');
      if(thingiview.getRotation()){
        cbx.checked = true;
      } else {
        cbx.checked = false;
      }
    });
    thingiview.initScene();
    //thingiview.loadSTL(urlParams['file']); //OK
    //thingiview.loadSTL('spring2d.stl');//OK
    thingiview.loadSTL('Tux.stl'); // OK
    //thingiview.loadSTL('Tux.3ds');
    //thingiview.loadSTL('Tux.dxf');
    //thingiview.loadSTL('Tux.obj');
    //thingiview.loadSTL('gun/GripRemodeled.STL');
    
    
  }

  function toggleWireframe(){
    if(thingiview.getObjectMaterial() == 'wireframe'){
      thingiview.setObjectMaterial('solid');
    } else {
      thingiview.setObjectMaterial('wireframe');
    }
  }

  function toggleRotation(){
    thingiview.setRotation( ! thingiview.getRotation() );
  }

  function getThingUrl(){
      return 'http://alcoor.com/thing:';
      /*
    if(urlParams['thing_id']){
      return 'http://www.thingiverse.com/thing:' + urlParams['thing_id'];
    } else {
      return 'http://www.thingiverse.com/';
    }*/
  }

  function setTitle(){
    thing_id = urlParams['thing_id'];
    thing_name = urlParams['thing'];
    creator = urlParams['creator'];
    if((thing_id != null) && (thing_name != null) && (creator != null)){
      $('title').insert({'top': '<a title="View on Thingiverse" target="_top" href="' + getThingUrl() + '">' + thing_name + ' by ' + creator + '</a>'});
    }
  }

  function setWatermarkLink(){
    $$('#watermark a')[0].href = getThingUrl();
  }

  function setSize(){
    var w = 630;
    var h = 473;
    if((urlParams['width'] != null) && (urlParams['height'] != null)){
      var tw = parseInt(urlParams['width']);
      var th = parseInt(urlParams['height']);
      if( (! isNaN(w)) && (! isNaN(h)) ){
        w = tw;
        h = th;
      }
    }
    $$('.main').each(function(e){
      e.style.width = '' + w + 'px';
      e.style.height = '' + h + 'px';
    });
    $$('.warning_container').each(function(e){
      e.style.height = '' + h + 'px';
    });
  }

  function getColor(name, default_color)
  {
    if(urlParams[name] != null){
      var colorstr = urlParams[name];
      if(/[0-9A-Fa-f]{6}/.test(colorstr)){
        return '#' + colorstr;
      }
    }
    return default_color;
  }

  function show_preview()
  {
    var aspect = 0.75;
    var prev_url = urlParams['preview'];
    var prev_w = 630;
    var prev_h = 473;
    var div = $('thing_preview');
    var innerdiv = $('thing_preview_imgdiv');
    var img = $('thing_preview_image');
    img.src = prev_url;
    // set image size based on preview div's size params
    var y_scale = div.getHeight() / prev_h;
    var x_scale = div.getWidth() / prev_w;
    var new_h;
    var new_w;
    if( x_scale < y_scale ){
      new_w = div.getWidth();
      new_h = prev_h * x_scale;
    } else {
      new_h = div.getHeight();
      new_w = prev_w * y_scale;
    }
    img.width = new_w;
    img.height = new_h;
    innerdiv.setStyle({width: new_w + 'px', height: new_h + 'px'});
    div.show();
    $$('.overlay').each(function(e){ e.show(); });
  }

  Event.observe(window,'load',
      function() {
        setSize();
        setTitle();
        //setWatermarkLink();
        if(!has_workers() || !has_canvas()){
          show_unsupported_message();
        } else if (!has_webgl()){
          show_warning_message();
        } else if (urlParams['preview']){
          show_preview();
        } else {
          show_thingiview();
        }
      });
</script>
<style>
.thingiview {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
}
body {
  background-color: #E6E6E6;
  color: #000000;
  font-family: Verdana,Helvetica,sans-serif;
  font-size: 13px;
  line-height: 1.3em;
  margin: 0px;
  padding: 0px;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}
label {
  font-size: small;
}
#controls {
  bottom: 10px;
  left: 10px;
  position: absolute;
  width: 220px;
}
#camera_control {
  float: left;
  margin-right: 5px;
}
#camera_buttons {
  margin-left: 20px;
  margin-top: 2px;
}
#watermark {
  bottom: 10px;
  position: absolute;
  right: 10px;
}
#watermark img {
  border: none;
  width: 210px;
}

#title {
  left: 10px;
  position: absolute;
  top: 10px;
}
#title a {
  color: #4F8EDD;
  font-family: Verdana, Helvetica, sans-serif;
  font-size: 1.5em;
  font-weight: bold;
  text-decoration: none;
}
#title a:hover {
  text-decoration: underline;
}

.overlay {
  /*opacity: 0.67;*/
}

.main {
  width: 630px;
  height: 473px;
}

.warning_container {
  display: table-cell;
  vertical-align: middle;
  width: 100%;
}

.warning {
  margin: 0 auto;
  width: 67%;
}

#thing_preview {
  display: table-cell;
  vertical-align: middle;
  width: 67%;
}

#thing_preview_imgdiv {
  margin: 0 auto;
}
</style>
</head>
<body>
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
  </div>
  <div class="thingiview ui main" id="thingiview" style="display:none;"></div>
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
  <div id="title" class="ui overlay" style="display:none">
  </div>
 
</body>
</html>