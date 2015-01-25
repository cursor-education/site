var el, scrollToTop, _i, _len, _ref;

scrollToTop = function() {
  var t;
  t = t || null;
  if (document.body.scrollTop !== 0 || document.documentElement.scrollTop !== 0) {
    window.scrollBy(0, -50);
    return t = setTimeout(scrollToTop, 5);
  } else {
    return clearTimeout(t);
  }
};

_ref = document.getElementsByClassName('go-to-top');
for (_i = 0, _len = _ref.length; _i < _len; _i++) {
  el = _ref[_i];
  el.onclick = scrollToTop;
}