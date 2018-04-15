/* Services */
angular.module('raw.services', [])
        .service('serviceUserInfo', 
            ['$http', '$q', function ($http, $q) {
                return function () {
                    var deferred = $q.defer();
                    
                    var promise = $http({method: 'GET',
                          headers: {'Content-Type': 'application/json',
                                    //importante para la deteccion
                                    // $request->isXmlHttpRequest() en symfony
                                    'X-Requested-With':'XMLHttpRequest',
                                    'Cache-Control': 'no-store'},
                           url: '/doc/userinfo'})
                        .success(function(data, status, headers, config) {
                                deferred.resolve(data);
                        })
                        .error(function(data, status, headers, config) {
                            //no esta logeado el usuario o lo que sea
                            deferred.reject(false);
                        });
                  
                    return deferred.promise;
                };
         }])
        .service('serviceLoadRaw', 
            ['$http', '$q', function ($http, $q) {
                return function () {
                
                    var deferred = $q.defer()
                    , url = window.location.pathname
                    , urlsplit = url.split("/")
                    , guiId = urlsplit[urlsplit.length-1];
                    
                    if (guiId === 'rawchart')
                    {
                      deferred.resolve({pass: false,
                                        title: false,
                                        content: false});      
                    }
                    else
                    {
                        var promise = $http({method: 'GET',
                          headers: {'Content-Type': 'application/json',
                                    //importante para la deteccion
                                    // $request->isXmlHttpRequest() en symfony
                                    'X-Requested-With':'XMLHttpRequest',
                                    'Cache-Control': 'no-store'},
                           url: url})
                        .success(function(data, status, headers, config) {
                            
                                data.pass = arquematics.crypt.decryptHexToString(data.pass);
                                data.title = arquematics.simpleCrypt.decryptBase64(data.pass, data.title);
                                data.content = JSON.parse(arquematics.simpleCrypt.decryptBase64(data.pass, data.content));     
                                
                                deferred.resolve(data);
                        })
                        .error(function(data, status, headers, config) {
                            //no esta logeado el usuario o lo que sea
                            deferred.reject(false);
                        });
                            
                    }
                    
                    return deferred.promise;
                };
         }])
        
	.factory('dataService',['$http', '$q', '$timeout', function ($http, $q, $timeout) {
		  
		  return {
		    
		    loadSample : function(sample){
		      var deferred = $q.defer();
		      $http.get(sample)
			      .then(function(response){
			          deferred.resolve(response.data);
			      },
			      function(){
			          deferred.reject("An error occured while getting sample (" + sample.title + ")");
			      });
		      
		      return deferred.promise;
		    },

		    debounce : function (func, wait, immediate) {
			    var timeout;
			    var deferred = $q.defer();
			    return function() {
			      var context = this, args = arguments;
			      var later = function() {
			        timeout = null;
			        if(!immediate) {
			          deferred.resolve(func.apply(context, args));
			          deferred = $q.defer();
			        }
			      };
			      var callNow = immediate && !timeout;
			      if ( timeout ) {
			        $timeout.cancel(timeout);
			      }
			      timeout = $timeout(later, wait);
			      if (callNow) {
			        deferred.resolve(func.apply(context,args));
			        deferred = $q.defer();
			      }
			      return deferred.promise;
			    };
			  }

	  	}
	}])