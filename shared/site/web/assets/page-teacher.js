/* 2015-03-16 17:03:16 */
var app;app={init:function(){return app.initScrollHook(),helpers.initTooltip()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();