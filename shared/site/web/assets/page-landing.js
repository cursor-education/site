/* 2015-03-16 20:03:10 */
var app;app={init:function(){return app.initScrollHook(),helpers.initScrollToTopEls(),helpers.initSubmitForm()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();