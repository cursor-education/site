var el, scrollToTop, _fn, _i, _len, _ref;

scrollToTop = function(val) {
  var diff, getScrollTop, isTopEqual, per, sign, t, topValue;
  t = t || null;
  val = val || 0;
  if (/^\d+$/.test(val)) {
    topValue = parseInt(val);
  } else {
    topValue = document.getElementById(val).offsetTop;
  }
  getScrollTop = function() {
    return document.body.scrollTop || document.documentElement.scrollTop;
  };
  isTopEqual = function(val) {
    return getScrollTop() === val;
  };
  if (!isTopEqual(topValue)) {
    per = 50;
    sign = +1;
    if (getScrollTop() > topValue) {
      sign = -1;
    }
    diff = (getScrollTop() - topValue) * -sign;
    if (diff > 0 && diff < per) {
      per = diff;
    }
    console.log(getScrollTop(), topValue, sign, diff, per);
    return window.scrollBy(0, sign * per);
  } else {
    return clearTimeout(t);
  }
};

_ref = document.getElementsByClassName('go-to-top');
_fn = function(el) {
  return el.onclick = function(e) {
    e.preventDefault();
    return scrollToTop(el.getAttribute('data-top'));
  };
};
for (_i = 0, _len = _ref.length; _i < _len; _i++) {
  el = _ref[_i];
  _fn(el);
}