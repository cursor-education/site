/* 2015-02-18 22:02:46 */
var app;app={init:function(){return app.initScrollHook(),helpers.initScrollToTopEls(),helpers.initSubmitForm()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();