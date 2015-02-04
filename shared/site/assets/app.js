var app, helpers;

helpers = {
  scrollTo: function(val, duration, done) {
    var e, easeOutCuaic, from, scrollToX, t, to;
    if (duration < 0) {
      return;
    }
    if (window.scrollToWorking === true) {
      return;
    }
    e = document.documentElement;
    if (e.scrollTop === 0) {
      t = e.scrollTop;
      ++e.scrollTop;
      if (t + 1 !== e.scrollTop--) {
        e = document.body;
      }
    }
    from = e.scrollTop;
    to = val;
    if (typeof from === "object") {
      from = from.offsetTop;
    }
    if (typeof to === "object") {
      to = to.offsetTop;
    }
    easeOutCuaic = function(t) {
      t--;
      return t * t * t + 1;
    };
    scrollToX = function(element, x1, x2, t, v, step, operacion, done1) {
      if (t < 0 || t > 1 || v <= 0) {
        return done1();
      }
      element.scrollTop = x1 - (x1 - x2) * operacion(t);
      t += v * step;
      return setTimeout(function() {
        return scrollToX(element, x1, x2, t, v, step, operacion, done1);
      }, step);
    };
    window.scrollToWorking = true;
    return scrollToX(e, from, to, 0, 1 / duration, 20, easeOutCuaic, function() {
      window.scrollToWorking = false;
      return done();
    });
  },
  scrollHook: function() {
    helpers.scrollHookHeader();
    return helpers.scrollHookMenu();
  },
  scrollHookHeader: function() {
    var className, classNameAnimate, fromEl, fromElOffset, headerMenuEl, scroll;
    scroll = document.body.scrollTop;
    headerMenuEl = document.getElementById('header-menu');
    if (headerMenuEl.hasAttribute('data-animate-from-element')) {
      fromEl = document.getElementById(headerMenuEl.getAttribute('data-animate-from-element'));
      fromElOffset = Math.max(fromEl.offsetTop, 100);
    } else {
      fromElOffset = Math.max(headerMenuEl.getAttribute('data-animate-from'), 100);
    }
    className = 'open';
    classNameAnimate = headerMenuEl.getAttribute('data-animate-effect');
    if (scroll >= (fromElOffset - 50)) {
      return headerMenuEl.classList.add(className);
    } else {
      return headerMenuEl.classList.remove(className);
    }
  },
  scrollHookMenuReset: function() {
    var classSelected, selectedEl, selectedEls, _i, _len, _results;
    classSelected = 'selected';
    selectedEls = document.getElementById('menu').getElementsByClassName(classSelected);
    _results = [];
    for (_i = 0, _len = selectedEls.length; _i < _len; _i++) {
      selectedEl = selectedEls[_i];
      _results.push(selectedEl.classList.remove(classSelected));
    }
    return _results;
  },
  scrollHookMenu: function() {
    var classSelected, dataTopEl, menuEl, menuEls, offsetMin, offsetTopMin, scroll, selectMenuEl, topEl, topElOffset, _i, _len;
    scroll = document.body.scrollTop;
    helpers.scrollHookMenuReset();
    classSelected = 'selected';
    menuEls = document.getElementById('menu').getElementsByTagName('a');
    offsetTopMin = -100;
    offsetMin = null;
    selectMenuEl = null;
    for (_i = 0, _len = menuEls.length; _i < _len; _i++) {
      menuEl = menuEls[_i];
      dataTopEl = menuEl.getAttribute('data-top');
      if (dataTopEl) {
        topEl = document.getElementById(dataTopEl);
        topElOffset = scroll - (topEl.offsetTop + offsetTopMin);
        if (topElOffset >= offsetTopMin) {
          if (offsetMin === null || offsetMin > Math.abs(topElOffset)) {
            selectMenuEl = menuEl;
          }
        }
      }
    }
    if (!(selectMenuEl != null ? selectMenuEl.classList.contains(classSelected) : void 0)) {
      return selectMenuEl != null ? selectMenuEl.classList.add(classSelected) : void 0;
    }
  },
  parallax: function() {
    var bgOffset, el, els, isElVisible, offsetY, _i, _len, _results;
    els = [document.getElementById('header'), document.getElementById('section-bg-inline')];
    offsetY = scroll;
    if (offsetY < 0) {
      offsetY = 0;
    }
    _results = [];
    for (_i = 0, _len = els.length; _i < _len; _i++) {
      el = els[_i];
      if (!el) {
        continue;
      }
      isElVisible = scroll > -1;
      isElVisible = isElVisible && scroll >= (el.offsetTop - el.offsetHeight);
      isElVisible = isElVisible && scroll <= (el.offsetTop + el.offsetHeight);
      if (isElVisible) {
        bgOffset = offsetY - el.offsetTop;
        _results.push(el.style.backgroundPositionY = bgOffset + "px");
      } else {
        _results.push(void 0);
      }
    }
    return _results;
  }
};

app = {
  init: function() {
    app.initScrollHook();
    app.initGoToTop();
    return app.initGallery();
  },
  initScrollHook: function() {
    window.onscroll = function() {
      return helpers.scrollHook();
    };
    return window.onscroll();
  },
  initGoToTop: function() {
    var el, _i, _len, _ref, _results;
    _ref = document.getElementsByClassName('go-to-top');
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      el = _ref[_i];
      _results.push((function(el) {
        return el.onclick = function(e) {
          var elementID, topOffset, topValue;
          e.preventDefault();
          elementID = el.getAttribute('data-top');
          if (elementID) {
            topValue = document.getElementById(elementID).offsetTop;
          } else {
            topValue = 0;
          }
          if (el.hasAttribute('data-top-offset')) {
            topOffset = parseInt(el.getAttribute('data-top-offset'));
            topValue += topOffset;
          }
          helpers.scrollHookMenuReset();
          el.classList.add('selected');
          return helpers.scrollTo(topValue, 1000, function() {
            helpers.scrollHookMenuReset();
            return el.classList.add('selected');
          });
        };
      })(el));
    }
    return _results;
  },
  initGallery: function() {
    var el, _i, _len, _ref, _results;
    _ref = document.getElementsByClassName('gallery');
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      el = _ref[_i];
      _results.push((function(el) {
        var els, elsClass, hiddenClass;
        hiddenClass = 'hidden';
        elsClass = el.getAttribute('data-gallery-class');
        els = el.getElementsByClassName(elsClass);
        return setInterval(function() {
          var num, _j, _len1;
          num = parseInt(Math.random() * els.length);
          for (_j = 0, _len1 = els.length; _j < _len1; _j++) {
            el = els[_j];
            if (!el.classList.contains(hiddenClass)) {
              el.classList.add(hiddenClass);
            }
          }
          els[num].classList.remove(hiddenClass);
          return console.log(11, el);
        }, 2000);
      })(el));
    }
    return _results;
  }
};

app.init();