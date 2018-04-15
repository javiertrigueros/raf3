/*global define*/
define([
    'sjcl',
    'arquematics'
], function ( sjcl, arquematics) {
    'use strict';

    var instance = null;

    function ArquematicsAuth () {
        var d = $.Deferred();

             $.ajax({type: "GET",
                 url: '/doc/userinfo',
                 datatype: 'json',
                 cache: false})
                .done(function(userInfo){
                    
                    if (userInfo && userInfo.encrypt)
                    {
                        arquematics.initEncrypt(
                            userInfo.user.id,
                            userInfo.store_key); 
                        
                        arquematics.crypt.setPublicEncKeys(userInfo.public_keys);
                    }
                    
                    d.resolve(userInfo);
                })
                .fail(function(error){
                   d.reject(false);
               });    
        
        return d;
    }

    return function (){
      return (instance = (instance || new ArquematicsAuth()));  
    }
});
