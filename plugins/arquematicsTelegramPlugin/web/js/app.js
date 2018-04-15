/*!
 * Webogram v0.0.17 - messaging web application for MTProto
 * https://github.com/zhukov/webogram
 * Copyright (C) 2014 Igor Zhukov <igor.beatle@gmail.com>
 * https://github.com/zhukov/webogram/blob/master/LICENSE
 */

'use strict';

window._testMode = location.search.indexOf('test=1') > 0;
window._debugMode = location.search.indexOf('debug=1') > 0;
window._osX = (navigator.platform || '').toLowerCase().indexOf('mac') !== -1 ||
              (navigator.userAgent || '').toLowerCase().indexOf('mac') !== -1;
window._retina = window.devicePixelRatio > 1;

if (!window._osX) {
  $('body').addClass('non_osx');
}
$('body').addClass(window._retina ? 'is_2x' : 'is_1x');

$(window).on('load', function () {
  setTimeout(function () {
    window.scrollTo(0,1);
  }, 0);
});

// Declare app level module which depends on filters, and services
var app = angular.module('myApp', [
  'ngRoute',
  'ngAnimate',
  'ngSanitize',
  'ui.bootstrap',
  'myApp.filters',
  'myApp.services',
  'mtproto.services',
  'myApp.directives',
  'myApp.controllers',
  'angularMoment',
  'gettext',
  'angularSidr'
]).
config(['$locationProvider', '$routeProvider', '$compileProvider', '$httpProvider', function($locationProvider, $routeProvider, $compileProvider, $httpProvider) {

  var icons = {}, reverseIcons = {}, i, j, hex, name, dataItem,
      ranges = [[0x1f600, 0x1f637], [0x261d, 0x263f], [0x270a, 0x270c], [0x1f446, 0x1f450]];

  for (j in ranges) {
    for (i = ranges[j][0]; i <= ranges[j][1]; i++) {
      hex = i.toString(16);
      dataItem = Config.Emoji[hex];
      if (dataItem) {
        name = dataItem[1][0];
        icons[':' + name + ':'] = hex + '.png';
        reverseIcons[name] = dataItem[0];
      }
    }
  }

  $.emojiarea.path = '/arquematicsTelegramPlugin/js/vendor/gemoji/images';
  $.emojiarea.icons = icons;
  $.emojiarea.reverseIcons = reverseIcons;

  $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|ftp|file|blob|filesystem|chrome-extension|app):|data:image\//);
  $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|file|mailto|blob|filesystem|chrome-extension|app):|data:image\//);


  //$locationProvider.html5Mode(true);
  $routeProvider.when('/', {templateUrl: '/arquematicsTelegramPlugin/js/partials/welcome.html', controller: 'AppWelcomeController'});
  $routeProvider.when('/login', {templateUrl: '/arquematicsTelegramPlugin/js/partials/login.html', controller: 'AppLoginController'});
  $routeProvider.when('/im', {templateUrl: '/arquematicsTelegramPlugin/js/partials/im.html', controller: 'AppIMController', reloadOnSearch: false});
  $routeProvider.otherwise({redirectTo: '/'});
  
 
}])

.value('globals', {
  "id":"1",
  "username":"admin",
  "long_name":"Normal admin",
  "email":"admin@arquematics",
  "phone":false,
  "key_saved":true,
  "public_key":"413963d86bcb438ea2f30933d626979316297de33a0c89f6bd6d9b07ada3e4862e8928af97665b7ad7c8010",
  "friends_keys":[{"id":"1","public_key":"413963d86bcb438ea2f30933d62697931629f6bd6d9b07df4443fe4862e8928af97665b7ad7c8010"}],
  "icon":"\/arquematicsPlugin\/images\/unknown.mini.jpg",
  "url":"\/user\/admin",
  "url_user_list":"\/group",
  "url_user_friend":"\/group\/friends",
  "lang":"es",
  "log_out":"\/logout",
  "cms_admin":"true"
  })
    
.run(function( $rootScope, $window, gettextCatalog, globals, $templateCache) {

   $rootScope.userConfig = globals;
   $rootScope.lang = globals.lang;
   
   gettextCatalog.currentLanguage = $rootScope.lang;
   gettextCatalog.loadRemote('/arquematicsTelegramPlugin/js/i18n/locale.' + $rootScope.lang + '.json');
                
   $window.moment.lang($rootScope.lang);
   
   
   /*
   //borra todas las caches de templates
   $rootScope.$on('$viewContentLoaded', function() {
      $templateCache.removeAll();
   });     
  */
   
  // change direction property when route changes
  $rootScope.direction = 'ltr';
  
  $rootScope.$on('$routeChangeStart', function(event, next, current) {
    $rootScope.direction = (current && next && (current.depth > next.depth)) ? 'ltr':'rtl';
  });
});
