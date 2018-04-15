var localization = false;

var app = angular.module('raw', [
  'ngRoute',
  'ngAnimate',
  'ngSanitize',
  'raw.filters',
  'raw.services',
  'raw.directives',
  'raw.controllers',
  'mgcrea.ngStrap',
  'ui',
  'colorpicker.module',
  'angularMoment',
  'gettext'
])

.config(['$routeProvider','$locationProvider', function ($routeProvider,$locationProvider) {
  $routeProvider.when('/docvector/rawchart/:guicode*', {templateUrl: '/arquematicsDocumentsPlugin/js/raw/partials/edit.html', controller: 'RawCtrlEditData'});
  $routeProvider.when('/docvector/rawchart', {templateUrl: '/arquematicsDocumentsPlugin/js/raw/partials/main.html', controller: 'RawCtrl'});
  
  //$routeProvider.otherwise({redirectTo: '/'});
  $locationProvider.html5Mode(true);
}])
.value('globals', {lang:'es', pass: false})
.run(['$rootScope',   'gettextCatalog', 'serviceLoadRaw', 'amMoment', 'serviceUserInfo', function( $rootScope,  gettextCatalog, serviceLoadRaw, amMoment, serviceUserInfo) {

   $rootScope.pass = false;
   $rootScope.lang = arquematics.lang;
   gettextCatalog.setCurrentLanguage($rootScope.lang);
   gettextCatalog.loadRemote('/arquematicsDocumentsPlugin/js/raw/i18n/locale.' + $rootScope.lang + '.json'); 
   
   amMoment.changeLocale($rootScope.lang);
   
   //informacion del usuario
   serviceUserInfo().then(function (userInfo) {
    
     if (userInfo && userInfo.encrypt)
     {
        arquematics.initEncrypt(
                            userInfo.user.id,
                            userInfo.store_key); 
                        
        arquematics.crypt.setPublicEncKeys(userInfo.public_keys);
        
        $rootScope.lang = userInfo.lang;
     }
    
     serviceLoadRaw().then(function (globals) {
      
        $rootScope.userConfig = globals;
        
        $rootScope.pass = globals.pass;
        $rootScope.title = globals.title;
        $rootScope.content = globals.content;
     });
   
   });
   
  
                
}]);