var initParallaxEffect = function () {
    var scroll = document.body.scrollTop;

    var el = document.getElementById('header');
    var elHeight = 650

    if (scroll <= -1 || scroll > elHeight) {
        return;
    }

    var scrollOffsetPx = -40;

    var scrollPx = scroll / 0.6;
    scrollPx += scrollOffsetPx;

    el.style.backgroundPositionY = scrollPx + "px";
};

window.onscroll = function () {
    initParallaxEffect();
}

window.onscroll();