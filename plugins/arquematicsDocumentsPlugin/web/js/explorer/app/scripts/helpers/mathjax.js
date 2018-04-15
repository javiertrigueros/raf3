/*global define*/
define([
    'mathjax'
], function (MathJax) {
    'use strict';

    MathJax.Hub.Config({
            messageStyle: "none", //no queremos info cuando se esta utilizando
            extensions: ["tex2jax.js"], 
            jax: ["input/TeX", "output/HTML-CSS"], 
            tex2jax: {
                preview: "none", //no queremos info cuando se esta utilizando
                inlineMath: [ ['$','$'], ["\\(","\\)"] ], 
                displayMath: [ ['$$','$$'], ["\\[","\\]"] ], 
                ignoreClass: "fancyvrb|verbatim", 
                processEscapes: true },
            "HTML-CSS": { availableFonts: ["TeX"] } 
      });

    return {
        init : function (el) {
            MathJax.Hub.Queue(['Typeset', MathJax.Hub, el]);
        }
    };
});
