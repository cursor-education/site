var el, scrollTo, _fn, _i, _len, _ref;

scrollTo = function(val, duration) {
  var e, easeOutCuaic, from, scrollToX, t, to;
  if (duration < 0) {
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
  scrollToX = function(element, x1, x2, t, v, step, operacion) {
    if (t < 0 || t > 1 || v <= 0) {
      return;
    }
    element.scrollTop = x1 - (x1 - x2) * operacion(t);
    t += v * step;
    return setTimeout(function() {
      return scrollToX(element, x1, x2, t, v, step, operacion);
    }, step);
  };
  return scrollToX(e, from, to, 0, 1 / duration, 20, easeOutCuaic);
};

_ref = document.getElementsByClassName('go-to-top');
_fn = function(el) {
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
    return scrollTo(topValue, 1000);
  };
};
for (_i = 0, _len = _ref.length; _i < _len; _i++) {
  el = _ref[_i];
  _fn(el);
}