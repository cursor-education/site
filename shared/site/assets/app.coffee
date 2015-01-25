initParallaxEffect = ->
    scroll = document.body.scrollTop

    els = [
        document.getElementById('header')
        document.getElementById('section-bg-inline')
    ]

    offsetY = scroll;
    offsetY = 0 if offsetY < 0;

    for el in els
        continue unless el

        isElVisible = scroll > -1
        isElVisible = isElVisible && scroll >= (el.offsetTop - el.offsetHeight)
        isElVisible = isElVisible && scroll <= (el.offsetTop + el.offsetHeight)

        if isElVisible
            bgOffset = offsetY - el.offsetTop
            el.style.backgroundPositionY = "#{bgOffset}px"

window.onscroll = ->
    initParallaxEffect()

window.onscroll()