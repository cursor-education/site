/* 2015-08-28 20:08:15 */
var app;app={init:function(){return app.initScrollHook(),helpers.initScrollToTopEls(),helpers.initSubmitForm(),helpers.initAbTesting(),helpers.initTooltip()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();