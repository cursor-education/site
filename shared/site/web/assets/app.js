/* 2015-09-03 19:09:16 */
window.helpers={scrollTo:function(a,b,c){var d,e,f,g,h,i;if(!(0>b)&&window.scrollToWorking!==!0)return d=document.documentElement,0===d.scrollTop&&(h=d.scrollTop,++d.scrollTop,h+1!==d.scrollTop--&&(d=document.body)),f=d.scrollTop,i=a,"object"==typeof f&&(f=f.offsetTop),"object"==typeof i&&(i=i.offsetTop),e=function(a){return a--,a*a*a+1},g=function(a,b,c,d,e,f,h,i){return 0>d||d>1||0>=e?i():(a.scrollTop=b-(b-c)*h(d),d+=e*f,setTimeout(function(){return g(a,b,c,d,e,f,h,i)},f))},window.scrollToWorking=!0,g(d,f,i,0,1/b,20,e,function(){return window.scrollToWorking=!1,c()})},initTooltip:function(){var a,b,c,d,e;return e=null,b=function(){return function(){var b,f,g,h,i,j;for(f=function(){return document.getElementsByClassName("tooltip")},document.getElementById("tooltip")||(e=document.createElement("div"),e.id="tooltip",document.body.appendChild(e)),i=f(),j=[],g=0,h=i.length;h>g;g++)b=i[g],b.hasAttribute("title")&&(b.setAttribute("data-tooltip",b.getAttribute("title")),b.removeAttribute("title")),b.addEventListener("mouseenter",d),b.addEventListener("mouseleave",a),j.push(b.addEventListener("mousemove",c));return j}}(this),d=function(){var a;return(a=this.getAttribute("data-tooltip"))?(document.getElementById("tooltip").innerHTML=a,document.getElementById("tooltip").style.width=7*a.length,document.getElementById("tooltip").style.display="block",null):void 0},a=function(){return document.getElementById("tooltip").style.display="none",null},c=function(a){var b,c,d,e;return b=document.getElementById("tooltip"),c=30,d=a.pageX,e=a.pageY,d+b.clientWidth+c>window.innerWidth&&(d=window.innerWidth-b.clientWidth-c),e+=10,d+=5,b.style.left=d+"px",b.style.top=e+"px",null},b()},initScrollToTopEls:function(){var a,b,c,d,e;for(d=document.getElementsByClassName("go-to-top"),e=[],b=0,c=d.length;c>b;b++)a=d[b],e.push(function(a){return a.onclick=function(b){var c,d,e;return b.preventDefault(),c=a.getAttribute("data-top"),e=c?document.getElementById(c).offsetTop:0,a.hasAttribute("data-top-offset")&&(d=parseInt(a.getAttribute("data-top-offset")),e+=d),helpers.scrollHookMenuReset(),a.classList.add("selected"),helpers.scrollTo(e,1e3,function(){return helpers.scrollHookMenuReset(),a.classList.add("selected")})}}(a));return e},scrollHook:function(){return helpers.scrollHookHeader(),helpers.scrollHookMenu(),helpers.scrollHookSections()},scrollHookHeader:function(){var a,b,c,d,e;return e=document.body.scrollTop,d=document.getElementById("header-menu"),d.hasAttribute("data-animate-from-element")?(b=document.getElementById(d.getAttribute("data-animate-from-element")),c=Math.max(b.offsetTop,100)):c=Math.max(d.getAttribute("data-animate-from"),100),a="open",e>=c-50?d.classList.add(a):d.classList.remove(a)},scrollHookMenuReset:function(){var a,b,c,d,e,f;for(a="selected",c=document.getElementById("menu").getElementsByClassName(a),f=[],d=0,e=c.length;e>d;d++)b=c[d],f.push(b.classList.remove(a));return f},scrollHookSections:function(){var a,b,c,d,e,f,g,h,i,j,k,l,m,n;for(d=document.body.scrollTop,a=document.getElementById("header-menu"),g=document.getElementsByClassName("section"),b=a.offsetHeight,n=[],c=k=m=g.length-1;k>=0;c=k+=-1){if(e=g[c],f=e.offsetTop,d+b>f){h="data-section-last",i=e.id,j="visible-section",a.classList.contains(j)||a.classList.add(j),a.hasAttribute(h,i)?(l=a.getAttribute(h,i),l!==i&&(a.classList.remove(j+"-"+l),a.setAttribute(h,i),a.classList.add(j+"-"+i))):(a.setAttribute(h,i),a.classList.add(j+"-"+i));break}n.push(void 0)}return n},scrollHookMenu:function(){var a,b,c,d,e,f,g,h,i,j,k,l;for(g=document.body.scrollTop,helpers.scrollHookMenuReset(),a="selected",d=document.getElementById("menu").getElementsByTagName("a"),f=-100,e=null,h=null,k=0,l=d.length;l>k;k++)if(c=d[k],b=c.getAttribute("data-top")){if(i=document.getElementById(b),!i)continue;j=g-(i.offsetTop+f),j>=f&&(null===e||e>Math.abs(j))&&(h=c)}return(null!=h?h.classList.contains(a):void 0)?void 0:null!=h?h.classList.add(a):void 0},parallax:function(){var a,b,c,d,e,f,g,h;for(c=[document.getElementById("header"),document.getElementById("section-bg-inline")],e=scroll,0>e&&(e=0),h=[],f=0,g=c.length;g>f;f++)b=c[f],b&&(d=scroll>-1,d=d&&scroll>=b.offsetTop-b.offsetHeight,d=d&&scroll<=b.offsetTop+b.offsetHeight,d?(a=e-b.offsetTop,h.push(b.style.backgroundPositionY=""+a+"px")):h.push(void 0));return h},ajax:function(a,b,c){var d,e;return d=function(){var a,b,c;try{c=new ActiveXObject("Msxml2.XMLHTTP")}catch(d){b=d;try{c=new ActiveXObject("Microsoft.XMLHTTP")}catch(d){a=d,c=!1}}return c||"undefined"==typeof XMLHttpRequest||(c=new XMLHttpRequest),c},e=d(),b?e.open("POST",a,!0):e.open("POST",a,!0),e.setRequestHeader("Content-Type","application/x-www-form-urlencoded"),e.onreadystatechange=function(){return 4===e.readyState?200===e.status?"function"==typeof c?c(e.responseText):void 0:handleError(e.statusText):void 0},e.send(b)},initSubmitForm:function(){var a,b,c,d,e;for(b=document.getElementsByClassName("light-form"),e=[],c=0,d=b.length;d>c;c++)a=b[c],e.push(function(a){return a.onsubmit=function(b){var c,d,e,f,g,h,i,j;if(b.preventDefault(),c="disabled",a.classList.contains(c))return!1;for(a.classList.add(c),e=a.getElementsByTagName("input"),f=[],g=0,h=e.length;h>g;g++)d=e[g],i=d.name,j=d.value,i&&f.push(i+"="+encodeURIComponent(j));return f=f.join("&"),helpers.ajax(a.getAttribute("action"),f,function(){return function(b){var f,g,h,i;for(f="ok"===b?document.getElementById("message-success").innerText:document.getElementById("message-failed").innerText,alert(f),a.classList.remove(c),i=[],g=0,h=e.length;h>g;g++)d=e[g],d.name&&i.push(d.value="");return i}}(this)),!1}}(a));return e},initAbTesting:function(){var a,b,c,d;if(-1!==(null!=(c=location.hash)?c.indexOf("#ab-"):void 0))return a=null!=(d=location.hash.match(/#ab-(.+?)$/))?d[1]:void 0,b={landing1:function(){var a,b;return a="Вчитися ніколи не пізно.<br>Ми навчаємо.",b=document.getElementsByClassName("slogan")[0].getElementsByTagName("span")[0],b.innerHTML=a},landing2:function(){var a,b;return a="Навчайся разом з нами.<br>Львівська школа програмування.",b=document.getElementsByClassName("slogan")[0].getElementsByTagName("span")[0],b.innerHTML=a}},"function"==typeof b[a]?b[a]():void 0}};