/* 2015-12-02 00:12:42 */
var app;app={init:function(){return app.initScrollHook(),helpers.initTooltip(),helpers.initSubmitForm(),helpers.initTooltip()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()}},app.init();