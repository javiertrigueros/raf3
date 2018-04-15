Thingiview = function(containerId) {
  scope = this;
  
  var container     = document.getElementById(containerId);
  
  // var stats    = null;
  var camera   = null;
  var scene    = null;
  var renderer = null;
  var object   = null;
  var plane    = null;
  
  var ambientLight     = null;
  var frontLight       = null;
  var backLight        = null;
  
  var targetXRotation             = 0;
  var targetXRotationOnMouseDown  = 0;
  var mouseX                      = 0;
  var mouseXOnMouseDown           = 0;

  var targetYRotation             = 0;
  var targetYRotationOnMouseDown  = 0;
  var mouseY                      = 0;
  var mouseYOnMouseDown           = 0;

  var mouseDown                  = false;
  var mouseOver                  = false;
  
  
  var mouseRightDown             = false;
  var targetXMove                = 0;
  var targetYMove                = 0;
  
  var windowHalfX = window.innerWidth / 2;
  var windowHalfY = window.innerHeight / 2;

  var view         = null;
  var infoMessage  = null;
  var progressBar  = null;
  var alertBox     = null;
  
  var timer        = null;

  var rotateTimer    = null;
  var rotateListener = null;
  var wasRotating    = null;

  var cameraView = 'diagonal';
  var cameraZoom = 0;
  //var rotate = false;
  var backgroundColor = '#606060';
  var objectMaterial = 'solid';
  var objectColor = 0xffffff;
  var showPlane = true;
  var isWebGl = false;

  var width = $(window).width();
  var height = $(window).height() - $('#header').height();

  var geometry;
  var testCanvas;
  
  var mouseButton =
          {
            left: 0,
            middle: 1,
            right: 2
          };
  
  this.toggleRotation = function() 
  {
    scope.setRotation( ! scope.getRotation());
  };
  
  this.toggleWireframe = function() 
  {
    
    if(scope.getObjectMaterial() === 'wireframe')
    {
      scope.setObjectMaterial('solid');
    }
    else 
    {
      scope.setObjectMaterial('wireframe');
    }
    
    if (timer === null) {
      // log('starting loop');
      timer = setInterval(sceneLoop, 1000/60);
    }
    
  };
  

  this.initScene = function() {
    container.style.position = 'relative';
    container.innerHTML      = '';
    
    $(container).css({ top: $('#header').height() + 'px' });
    
    camera = new THREE.Camera(45, width/ height, 1, 100000);
    camera.updateMatrix();

    scene  = new THREE.Scene();

    ambientLight = new THREE.AmbientLight(0xffffff);
    scene.addLight(ambientLight);
    
    frontLight = new THREE.DirectionalLight(0x808080, 0.35);
    frontLight.position.x = 1;
    frontLight.position.y = 1;
    frontLight.position.z = 2;
    frontLight.position.normalize();
    scene.addLight(frontLight);
    
    backLight = new THREE.DirectionalLight(0x808080, 0.35);
    backLight.position.x = -1;
    backLight.position.y = -1;
    backLight.position.z = 2;
    backLight.position.normalize();
    scene.addLight(backLight);

    progressBar = document.createElement('div');
    progressBar.style.position = 'absolute';
    progressBar.style.top = '0px';
    progressBar.style.left = '0px';
    progressBar.style.backgroundColor = 'red';
    progressBar.style.padding = '5px';
    progressBar.style.display = 'none';
    progressBar.style.overflow = 'visible';
    progressBar.style.whiteSpace = 'nowrap';
    progressBar.style.zIndex = 100;
    container.appendChild(progressBar);
    
    alertBox = document.createElement('div');
    alertBox.id = 'alertBox';
    alertBox.style.position = 'absolute';
    alertBox.style.top = '25%';
    alertBox.style.left = '25%';
    alertBox.style.width = '50%';
    alertBox.style.height = '50%';
    alertBox.style.backgroundColor = '#dddddd';
    alertBox.style.padding = '10px';
    // alertBox.style.overflowY = 'scroll';
    alertBox.style.display = 'none';
    alertBox.style.zIndex = 100;
    container.appendChild(alertBox);
    
    if (showPlane) {
      loadPlaneGeometry();
    }
    
    this.setCameraView(cameraView);
    this.setObjectMaterial(objectMaterial);

    testCanvas = document.createElement('canvas');
    try {
      if (testCanvas.getContext('experimental-webgl')) {
        // showPlane = false;
        isWebGl = true;
        renderer = new THREE.WebGLRenderer();
        renderer.gammaOutput = true;
        // renderer = new THREE.CanvasRenderer();
      } else {
        renderer = new THREE.CanvasRenderer();
      }
    } catch(e) {
      renderer = new THREE.CanvasRenderer();
      // log("failed webgl detection");
    }

    // renderer.setSize(container.innerWidth, container.innerHeight);

    renderer.setSize(width, height);
    
    renderer.domElement.style.backgroundColor = backgroundColor;
    
    container.appendChild(renderer.domElement);

    // stats = new Stats();
    // stats.domElement.style.position  = 'absolute';
    // stats.domElement.style.top       = '0px';
    // container.appendChild(stats.domElement);

    renderer.domElement.addEventListener('mousemove',      onRendererMouseMove,     false);    
    //window.addEventListener('mousemove',      onRendererMouseMove,     false);    
    renderer.domElement.addEventListener('mouseover',      onRendererMouseOver,     false);
    renderer.domElement.addEventListener('mouseout',       onRendererMouseOut,      false);
    renderer.domElement.addEventListener('mousedown',      onRendererMouseDown,     false);
    renderer.domElement.addEventListener('mouseup',        onRendererMouseUp,       false);
    //window.addEventListener('mouseup',        onRendererMouseUp,       false);

    renderer.domElement.addEventListener('touchstart',     onRendererTouchStart,    false);
    renderer.domElement.addEventListener('touchend',       onRendererTouchEnd,      false);
    renderer.domElement.addEventListener('touchmove',      onRendererTouchMove,     false);

    renderer.domElement.addEventListener('DOMMouseScroll', onRendererScroll,        false);
    renderer.domElement.addEventListener('mousewheel',     onRendererScroll,        false);
    renderer.domElement.addEventListener('gesturechange',  onRendererGestureChange, false);
  }

  onRendererScroll = function(event) {
    event.preventDefault();

    var rolled = 0;

    if (event.wheelDelta === undefined) {
      // Firefox
      // The measurement units of the detail and wheelDelta properties are different.
      rolled = -40 * event.detail;
    } else {
      rolled = event.wheelDelta;
    }

    if (rolled > 0) {
      // up
      scope.setCameraZoom(+2);
    } else {
      // down
      scope.setCameraZoom(-2);
    }
  }

  onRendererGestureChange = function(event) {
    event.preventDefault();

    if (event.scale > 1) {
      scope.setCameraZoom(+5);
    } else {
      scope.setCameraZoom(-5);
    }
  }

  onRendererMouseOver = function(event) {
    mouseOver = true;
    // targetRotation = object.rotation.z;
    if (timer === null) {
      // log('starting loop');
      timer = setInterval(sceneLoop, 1000/60);
    }
  }

  onRendererMouseDown = function(e) {
      
    e.stopPropagation();
    e.cancelBubble = true;
        
    if (e.button === mouseButton.left)
    {
        mouseDown = true;
    
        if(scope.getRotation())
        {
            wasRotating = true;
            scope.setRotation(false);
        } else {
            wasRotating = false;
        }
    
        mouseXOnMouseDown = e.clientX - windowHalfX;
        mouseYOnMouseDown = e.clientY - windowHalfY;

        targetXRotationOnMouseDown = targetXRotation;
        targetYRotationOnMouseDown = targetYRotation;     
    }
    else if (e.button === mouseButton.right)
    {
       mouseRightDown = true;
       
       mouseXOnMouseDown = e.clientX - windowHalfX;
       mouseYOnMouseDown = e.clientY - windowHalfY;
       
       targetXMove = object.position.x;
       targetYMove = object.position.y;
       
        
       //targetXMoveOnMouseDown = mouseXOnMouseDown;
       //targetYMoveOnMouseDown = mouseYOnMouseDown;
       
    }
    
    return false;
    
  };

  onRendererMouseMove = function(event) {
    // log("move");

    if (mouseDown) 
    {
  	  mouseX = event.clientX - windowHalfX;
      // targetXRotation = targetXRotationOnMouseDown + (mouseX - mouseXOnMouseDown) * 0.02;
  	//  xrot = ;

  	  mouseY = event.clientY - windowHalfY;
      // targetYRotation = targetYRotationOnMouseDown + (mouseY - mouseYOnMouseDown) * 0.02;
  	 // yrot = ;
  	  
  	  targetXRotation = targetXRotationOnMouseDown + (mouseX - mouseXOnMouseDown) * 0.02;
  	  targetYRotation = targetYRotationOnMouseDown + (mouseY - mouseYOnMouseDown) * 0.02;
    }
    else if (mouseRightDown)
    {
         mouseX = event.clientX - windowHalfX;
         mouseY = event.clientY - windowHalfY;
         
         //targetXMove = (targetXMoveOnMouseDown + (mouseX - mouseXOnMouseDown)) * 0.002;
         //targetYMove = targetYMoveOnMouseDown + (mouseY - mouseYOnMouseDown);
         targetXMove +=  ((mouseX - mouseXOnMouseDown) * 0.007);
         targetYMove +=  ((mouseYOnMouseDown -  mouseY) * 0.007);
    }
  };

  onRendererMouseUp = function(event) {
    // log("up");
    if (mouseDown) 
    {
      mouseDown = false;
      if (!mouseOver) {
        clearInterval(timer);
        timer = null;
      }
      if (wasRotating) {
        scope.setRotation(true);
      }
    }
    else if (mouseRightDown)
    {
       
       mouseRightDown = false;
       
    }
  };

  onRendererMouseOut = function(event) {
    if (!mouseDown) {
      clearInterval(timer);
      timer = null;
    }
    mouseOver = false;
  }

  onRendererTouchStart = function(event) {
    targetXRotation = object.rotation.z;
    targetYRotation = object.rotation.x;

    timer = setInterval(sceneLoop, 1000/60);

  	if (event.touches.length == 1) {
  		event.preventDefault();

  		mouseXOnMouseDown = event.touches[0].pageX - windowHalfX;
  		targetXRotationOnMouseDown = targetXRotation;

  		mouseYOnMouseDown = event.touches[0].pageY - windowHalfY;
  		targetYRotationOnMouseDown = targetYRotation;
  	}
  }

  onRendererTouchEnd = function(event) {
    clearInterval(timer);
    timer = null;
    // targetXRotation = object.rotation.z;
    // targetYRotation = object.rotation.x;
  }

  onRendererTouchMove = function(event) {
  	if (event.touches.length == 1) {
  		event.preventDefault();

  		mouseX = event.touches[0].pageX - windowHalfX;
  		targetXRotation = targetXRotationOnMouseDown + (mouseX - mouseXOnMouseDown) * 0.05;

  		mouseY = event.touches[0].pageY - windowHalfY;
  		targetYRotation = targetYRotationOnMouseDown + (mouseY - mouseYOnMouseDown) * 0.05;
  	}
  }

  sceneLoop = function() {
    if (object) {
      // if (view == 'bottom') {
      //   if (showPlane) {
      //     plane.rotation.z = object.rotation.z -= (targetRotation + object.rotation.z) * 0.05;
      //   } else {
      //     object.rotation.z -= (targetRotation + object.rotation.z) * 0.05;
      //   }
      // } else {
      //   if (showPlane) {
      //     plane.rotation.z = object.rotation.z += (targetRotation - object.rotation.z) * 0.05;
      //   } else {
      //     object.rotation.z += (targetRotation - object.rotation.z) * 0.05;
      //   }
      // }

      if (showPlane) {
         
        object.position.x = targetXMove;
        object.position.y = targetYMove;
          
        plane.position.x = targetXMove;
        plane.position.y = targetYMove;
       
        //plane.position.y = object.position.y;
        //plane.position.z = object.position.z = (targetXRotation - object.position.z);
        //plane.rotation.y = object.rotation.y = (targetXMove - object.rotation.z) * 0.2;
        plane.rotation.z = object.rotation.z = (targetXRotation - object.rotation.z) * 0.2;
        plane.rotation.x = object.rotation.x = (targetYRotation - object.rotation.x) * 0.2;
      } else {
        //object.rotation.y = (targetXMove - object.rotation.z) * 0.2;
        object.rotation.z = (targetXRotation - object.rotation.z) * 0.2;
        object.rotation.x = (targetYRotation - object.rotation.x) * 0.2;
      
        object.position.x = targetXMove;
        object.position.y = targetYMove;
        
        //targetXMove = 0;
        //object.position.y;
       
      }

      // log(object.rotation.x);

      camera.updateMatrix();
      object.updateMatrix();
      
      if (showPlane) {
        plane.updateMatrix();
      }

    	renderer.render(scene, camera);
      // stats.update();
    }
  }

  rotateLoop = function() {
    // targetRotation += 0.01;
    targetXRotation += 0.05;
    sceneLoop();
  }

  this.getShowPlane = function(){
    return showPlane;
  }

  this.setShowPlane = function(show) {
    showPlane = show;
    
    if (show) {
      if (scene && !plane) {
        loadPlaneGeometry();
      }
      plane.material[0].opacity = 1;
      // plane.updateMatrix();
    } else {
      if (scene && plane) {
        // alert(plane.material[0].opacity);
        plane.material[0].opacity = 0;
        // plane.updateMatrix();
      }
    }
    
    sceneLoop();
  };

  this.getRotation = function() {
    return rotateTimer !== null;
  };

  this.setRotation = function(rotate) {

    
    if (rotate) {
      rotateTimer = setInterval(rotateLoop, 1000/60);
    } else {
      clearInterval(rotateTimer);
      rotateTimer = null;
    }

    scope.onSetRotation();
  };

  this.onSetRotation = function(callback) {
    if(callback === undefined){
      if(rotateListener !== null){
        try{
          rotateListener(scope.getRotation());
        } catch(ignored) {}
      }
    } else {
      rotateListener = callback;
    }
  }

  this.setCameraView = function(dir) {
    cameraView = dir;

    targetXRotation       = 0;
    targetYRotation       = 0;

    if (object) {
      object.rotation.x = 0;
      object.rotation.y = 0;
      object.rotation.z = 0;
    }

    if (showPlane && object) {
      plane.rotation.x = object.rotation.x;
      plane.rotation.y = object.rotation.y;
      plane.rotation.z = object.rotation.z;
    }
    
    if (dir === 'top') {
      // camera.position.y = 0;
      // camera.position.z = 100;
      if (showPlane) {
        plane.flipSided = false;
      }
    } else if (dir === 'side') {
      camera.position.y = -70;
      camera.position.z = 70;
      targetYRotation = -4.0;
      targetXRotation = -9.5;
      camera.target.position.z = 0;
      if (showPlane) {
        plane.flipSided = false;
      }
    } else if (dir === 'front') {
      camera.position.y = -70;
      camera.position.z = 70;
      targetYRotation = -4.0;
      camera.target.position.z = 0;
      if (showPlane) {
        plane.flipSided = false;
      }
    } else if (dir === 'front') {
      camera.position.y = -70;
      camera.position.z = 70;
      targetYRotation = -4.0;
      camera.target.position.z = 0;
      if (showPlane) {
        plane.flipSided = false;
      }
    } else if (dir === 'bottom') {
      // camera.position.y = 0;
      // camera.position.z = -100;
      if (showPlane) {
        plane.flipSided = true;
      }
    } else {
      camera.position.y = -70;
      camera.position.z = 70;
      targetXRotation = -9.5;
      camera.target.position.z = 0;
      if (showPlane) {
        plane.flipSided = false;
      }
    }

    mouseX            = targetXRotation;
    mouseXOnMouseDown = targetXRotation;
    
    mouseY            = targetYRotation;
    mouseYOnMouseDown = targetYRotation;
    
    scope.centerCamera();
    
    sceneLoop();
  }
  
  this.getCameraZoom = function()
  {
     return cameraZoom; 
  };

  this.setCameraZoom = function(factor) {
    cameraZoom += factor;
    
    if (cameraView == 'bottom') {
      if (camera.position.z + factor > 0) {
        factor = 0;
      }
    } else {
      if (camera.position.z - factor < 0) {
        factor = 0;
      }
    }
    
    if (cameraView == 'top') {
      camera.position.z -= factor;
    } else if (cameraView == 'bottom') {
      camera.position.z += factor;
    } else if (cameraView == 'side') {
      camera.position.y += factor;
      camera.position.z -= factor;
    } else {
      camera.position.y += factor;
      camera.position.z -= factor;
    }

    sceneLoop();
  }

  this.getObjectMaterial = function() {
    return objectMaterial;
  }

  this.setObjectMaterial = function(type) {
    objectMaterial = type;

    loadObjectGeometry();
  }

  this.setBackgroundColor = function(color) {
    backgroundColor = color
    
    if (renderer) {
      renderer.domElement.style.backgroundColor = color;
    }
  }

  this.setObjectColor = function(color) {
    objectColor = parseInt(color.replace(/\#/g, ''), 16);
    
    loadObjectGeometry();
  }

  this.loadSTL = function(url) {
    scope.newWorker('loadSTL', url);
  }

  this.loadOBJ = function(url) {
    scope.newWorker('loadOBJ', url);
  }
  
  this.loadSTLString = function(STLString) {
    scope.newWorker('loadSTLString', STLString);
  }
  
  this.loadSTLBinary = function(STLBinary) {
    scope.newWorker('loadSTLBinary', STLBinary);
  }
  
  this.loadOBJString = function(OBJString) {
    scope.newWorker('loadOBJString', OBJString);
  }

  this.loadJSON = function(url) {
    scope.newWorker('loadJSON', url);
  }
  
  this.getCentroid = function ( mesh ) {

    mesh.geometry.computeBoundingBox();
    var boundingBox = mesh.geometry.boundingBox;

    var x0 = boundingBox.x[ 0 ];
    var x1 = boundingBox.x[ 1 ];
    var y0 = boundingBox.y[ 0 ];
    var y1 = boundingBox.y[ 1 ];
    var z0 = boundingBox.z[ 0 ];
    var z1 = boundingBox.z[ 1 ];


    var bWidth = ( x0 > x1 ) ? x0 - x1 : x1 - x0;
    var bHeight = ( y0 > y1 ) ? y0 - y1 : y1 - y0;
    var bDepth = ( z0 > z1 ) ? z0 - z1 : z1 - z0;

    var centroidX = x0 + ( bWidth / 2 ) + mesh.position.x;
    var centroidY = y0 + ( bHeight / 2 )+ mesh.position.y;
    var centroidZ = z0 + ( bDepth / 2 ) + mesh.position.z;

    return mesh.geometry.centroid = { x : centroidX, y : centroidY, z : centroidZ };

}

  this.centerCamera = function() {
    if (geometry) {
       
       //scope.getCentroid(object);
    
        /*
        geometry.computeBoundingBox();

        var centerX = 0.5 * ( geometry.boundingBox.x[ 1 ] - geometry.boundingBox.x[ 0 ] );
        var centerY = 0.5 * ( geometry.boundingBox.y[ 1 ] - geometry.boundingBox.y[ 0 ] );
        var centerZ = 0.5 * ( geometry.boundingBox.z[ 1 ] - geometry.boundingBox.z[ 0 ] );
        
        camera.target.position.copy( object.position );
        camera.target.position.addSelf( new THREE.Vector3( centerX, centerY, centerZ ) );
        
        frontLight.position.x = geometry.min_x * 3;
        frontLight.position.y = geometry.min_y * 3;
        frontLight.position.z = geometry.max_z * 2;

        backLight.position.x = 0;
        backLight.position.y = 0;
        backLight.position.z = geometry.max_z * 4;
        */
        // original
      // Using method from http://msdn.microsoft.com/en-us/library/bb197900(v=xnagamestudio.10).aspx
      // log("bounding sphere radius = " + geometry.boundingSphere.radius);

      // look at the center of the object
      camera.target.position.x = geometry.center_x;
      camera.target.position.y = geometry.center_y;
      camera.target.position.z = geometry.center_z;

      // set camera position to center of sphere
      camera.position.x = geometry.center_x;
      camera.position.y = geometry.center_y;
      camera.position.z = geometry.center_z;

      // find distance to center
      distance = geometry.boundingSphere.radius / Math.sin((camera.fov/2) * (Math.PI / 180));

      // zoom backwards about half that distance, I don't think I'm doing the math or backwards vector calculation correctly?
      // scope.setCameraZoom(-distance/1.8);
      scope.setCameraZoom(-distance/1.5);
      //scope.setCameraZoom(-distance/1.9);

      frontLight.position.x = geometry.min_x * 3;
      frontLight.position.y = geometry.min_y * 3;
      frontLight.position.z = geometry.max_z * 2;

      backLight.position.x = 0;
      backLight.position.y = 0;
      backLight.position.z = geometry.max_z * 4;
          
         
      /*
      geometry.centroid = new THREE.Vector3();

    for ( var i = 0, l = geometry.vertices.length; i < l; i ++ ) {

        geometry.centroid.addSelf( geometry.vertices[ i ].position );

    } 

    geometry.centroid.divideScalar( geometry.vertices.length );
      */
      
    } else {
      // set to any valid position so it doesn't fail before geometry is available
      camera.position.y = -70;
      camera.position.z = 70;
      camera.target.position.z = 0;
    }
  }

  this.loadArray = function(array) {
    log("loading array...");
    geometry = new STLGeometry(array);
    loadObjectGeometry();
    scope.setRotation(false);
    scope.centerCamera();
    log("finished loading " + geometry.faces.length + " faces.");
  }

  this.newWorker = function(cmd, param) {
  	
    var worker = new WorkerFacade(thingiurlbase + '/thingiloader.js');
    
    worker.onmessage = function(event) {
      if (event.data.status === "complete") {
        progressBar.innerHTML = 'Initializing geometry...';
        // scene.removeObject(object);
        geometry = new STLGeometry(event.data.content);
        loadObjectGeometry();
        progressBar.innerHTML = '';
        progressBar.style.display = 'none';

        log("finished loading " + geometry.faces.length + " faces.");
        scope.centerCamera();
        sceneLoop();
      } else if (event.data.status == "progress") {
        progressBar.style.display = 'block';
        progressBar.style.width = event.data.content;
        // log(event.data.content);
      } else if (event.data.status == "message") {
        progressBar.style.display = 'block';
        progressBar.innerHTML = event.data.content;
        // log(event.data.content);
      } else if (event.data.status == "alert") {
        scope.displayAlert(event.data.content);
      } else {
        alert('Error: ' + event.data);
        log('Unknown Worker Message: ' + event.data);
      }
    }

    worker.onerror = function(error) {
      log(error);
      error.preventDefault();
    }

    worker.postMessage({'cmd':cmd, 'param':param});
  }

  this.displayAlert = function(msg) {
    msg = msg + "<br/><br/><center><input type=\"button\" value=\"Ok\" onclick=\"document.getElementById('alertBox').style.display='none'\"></center>"
    
    alertBox.innerHTML = msg;
    alertBox.style.display = 'block';
    
    // log(msg);
  }

  function loadPlaneGeometry() {
    // TODO: switch to lines instead of the Plane object so we can get rid of the horizontal lines in canvas renderer...
    plane = new THREE.Mesh(new Plane(100, 100, 16, 16), new THREE.LineBasicMaterial({color:0xe0e0e0,linewidth:2.5}));
    scene.addObject(plane);
  }

  function loadObjectGeometry() {
    if (scene && geometry) {
      if (objectMaterial == 'wireframe') {
        // material = new THREE.MeshColorStrokeMaterial(objectColor, 1, 1);
        material = new THREE.MeshBasicMaterial({color:objectColor,wireframe:true});
      } else {
        if (isWebGl) {
          material = new THREE.MeshPhongMaterial({color:objectColor});
          // material = new THREE.MeshColorFillMaterial(objectColor);
          // material = new THREE.MeshLambertMaterial({color:objectColor});
          //material = new THREE.MeshLambertMaterial({color:objectColor, shading: THREE.FlatShading});
        } else {
          material = new THREE.MeshLambertMaterial({color:objectColor, shading: THREE.FlatShading});
        }
      }

      // scene.removeObject(object);      

      if (object) {
        // shouldn't be needed, but this fixes a bug with webgl not removing previous object when loading a new one dynamically
        object.materials = [new THREE.MeshBasicMaterial({color:0xffffff, opacity:0})];
        scene.removeObject(object);        
        // object.geometry = geometry;
        // object.materials = [material];
      }

      object = new THREE.Mesh(geometry, material);
  		scene.addObject(object);

      if (objectMaterial != 'wireframe') {
        object.overdraw = true;
        object.doubleSided = true;
      }
      
      object.updateMatrix();
    
      //targetXRotation = 0;
      //targetYRotation = 0;

      sceneLoop();
    }
  }

};

var STLGeometry = function(stlArray) {
  // log("building geometry...");
	THREE.Geometry.call(this);

	var scope = this;

  // var vertexes = stlArray[0];
  // var normals  = stlArray[1];
  // var faces    = stlArray[2];

  for (var i=0; i<stlArray[0].length; i++) {    
    v(stlArray[0][i][0], stlArray[0][i][1], stlArray[0][i][2]);
  }

  for (var i=0; i<stlArray[1].length; i++) {
    f3(stlArray[1][i][0], stlArray[1][i][1], stlArray[1][i][2]);
  }

  function v(x, y, z) {
    // log("adding vertex: " + x + "," + y + "," + z);
    scope.vertices.push( new THREE.Vertex( new THREE.Vector3( x, y, z ) ) );
  }

  function f3(a, b, c) {
    // log("adding face: " + a + "," + b + "," + c)
    scope.faces.push( new THREE.Face3( a, b, c ) );
  }

  // log("computing centroids...");
  this.computeCentroids();
  // log("computing normals...");
  // this.computeNormals();
  this.computeFaceNormals();
  this.sortFacesByMaterial();
  // log("finished building geometry");

  scope.min_x = 0;
  scope.min_y = 0;
  scope.min_z = 0;
  
  scope.max_x = 0;
  scope.max_y = 0;
  scope.max_z = 0;
  
  for (var v = 0, vl = scope.vertices.length; v < vl; v ++) {
		scope.max_x = Math.max(scope.max_x, scope.vertices[v].position.x);
		scope.max_y = Math.max(scope.max_y, scope.vertices[v].position.y);
		scope.max_z = Math.max(scope.max_z, scope.vertices[v].position.z);
		                                    
		scope.min_x = Math.min(scope.min_x, scope.vertices[v].position.x);
		scope.min_y = Math.min(scope.min_y, scope.vertices[v].position.y);
		scope.min_z = Math.min(scope.min_z, scope.vertices[v].position.z);
}

  scope.center_x = (scope.max_x + scope.min_x)/2;
  scope.center_y = (scope.max_y + scope.min_y)/2;
  scope.center_z = (scope.max_z + scope.min_z)/2;
}

STLGeometry.prototype = new THREE.Geometry();
STLGeometry.prototype.constructor = STLGeometry;

function log(msg) {
  if (this.console) {
    console.log(msg);
  }
}

/* A facade for the Web Worker API that fakes it in case it's missing. 
Good when web workers aren't supported in the browser, but it's still fast enough, so execution doesn't hang too badly (e.g. Opera 10.5).
By Stefan Wehrmeyer, licensed under MIT
*/

var WorkerFacade;
if(!!window.Worker){
    WorkerFacade = (function(){
        return function(path){
            return new window.Worker(path);
        };
    }());
} else {
    WorkerFacade = (function(){
        var workers = {}, masters = {}, loaded = false;
        var that = function(path){
            var theworker = {}, loaded = false, callings = [];
            theworker.postToWorkerFunction = function(args){
                try{
                    workers[path]({"data":args});
                }catch(err){
                    theworker.onerror(err);
                }
            };
            theworker.postMessage = function(params){
                if(!loaded){
                    callings.push(params);
                    return;
                }
                theworker.postToWorkerFunction(params);
            };
            masters[path] = theworker;
            var scr = document.createElement("SCRIPT");
            scr.src = path;
            scr.type = "text/javascript";
            scr.onload = function(){
                loaded = true;
                while(callings.length > 0){
                    theworker.postToWorkerFunction(callings[0]);
                    callings.shift();
                }
            };
            document.body.appendChild(scr);
            
            var binaryscr = document.createElement("SCRIPT");
            binaryscr.src = thingiurlbase + '/binaryReader.js';
            binaryscr.type = "text/javascript";
            document.body.appendChild(binaryscr);
            
            return theworker;
        };
        that.fake = true;
        that.add = function(pth, worker){
            workers[pth] = worker;
            return function(param){
                masters[pth].onmessage({"data": param});
            };
        };
        that.toString = function(){
            return "FakeWorker('"+path+"')";
        };
        return that;
    }());
}

/* Then just use WorkerFacade instead of Worker (or alias it)

The Worker code must should use a custom function (name it how you want) instead of postMessage.
Put this at the end of the Worker:

if(typeof(window) === "undefined"){
    onmessage = nameOfWorkerFunction;
    customPostMessage = postMessage;
} else {
    customPostMessage = WorkerFacade.add("path/to/thisworker.js", nameOfWorkerFunction);
}

*/
