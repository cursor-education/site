/* 2015-11-22 22:11:51 */
var app;app={init:function(){return app.initScrollHook(),helpers.initScrollToTopEls(),helpers.initSubmitForm(),helpers.initAbTesting(),helpers.initTooltip(),app.initChoosingOfDirection()},initScrollHook:function(){return window.onscroll=function(){return helpers.scrollHook()},window.onscroll()},initChoosingOfDirection:function(){var a,b,c,d,e,f;if(c=document.getElementsByClassName("directions-types")[0].getElementsByTagName("h2"),a=document.getElementsByClassName("direction-list")[0].getElementsByClassName("rows"),c.length>1){for(f=[],d=0,e=c.length;e>d;d++)b=c[d],f.push(function(b){return b.onclick=function(d){var e,f,g,h,i,j,k;for(d.preventDefault(),h=0,j=c.length;j>h;h++)f=c[h],f.classList.remove("active");for(b.classList.add("active"),g=b.getAttribute("data-direction"),i=0,k=a.length;k>i;i++)e=a[i],e.classList.add("hidden");return e=document.getElementById("direction-"+g+"-list"),e.classList.remove("hidden")}}(b));return f}}},app.init();