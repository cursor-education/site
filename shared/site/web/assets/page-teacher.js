/* 2015-03-27 16:03:21 */
var app;app={init:function(){return app.initScrollHook(),helpers.initTooltip()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();