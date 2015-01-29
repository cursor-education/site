scrollTo = (val, duration) ->
    return if duration < 0

    e = document.documentElement

    if e.scrollTop == 0
        t = e.scrollTop

        ++e.scrollTop

        if t+1 != e.scrollTop--
            e = document.body

    from = e.scrollTop
    to = val

    from = from.offsetTop if typeof from == "object"
    to = to.offsetTop if typeof to == "object"

    easeOutCuaic = (t) ->
        t--
        t*t*t+1

    scrollToX = (element, x1, x2, t, v, step, operacion) ->
        return if t < 0 || t > 1 || v <= 0

        element.scrollTop = x1 - (x1-x2) * operacion(t);
        t += v * step

        setTimeout(->
            scrollToX element, x1, x2, t, v, step, operacion
        , step)

    scrollToX e, from, to, 0, 1/duration, 20, easeOutCuaic

for el in document.getElementsByClassName('go-to-top')
    do (el) ->
        el.onclick = (e) ->
            e.preventDefault();

            elementID = el.getAttribute('data-top');

            if elementID
                topValue = document.getElementById(elementID).offsetTop
            else
                topValue = 0

            if el.hasAttribute('data-top-offset')
                topOffset = parseInt el.getAttribute('data-top-offset')
                topValue += topOffset

            scrollTo topValue, 1000

# initParallaxEffect = ->
#     scroll = document.body.scrollTop

#     els = [
#         document.getElementById('header')
#         document.getElementById('section-bg-inline')
#     ]

#     offsetY = scroll;
#     offsetY = 0 if offsetY < 0;

#     for el in els
#         continue unless el

#         isElVisible = scroll > -1
#         isElVisible = isElVisible && scroll >= (el.offsetTop - el.offsetHeight)
#         isElVisible = isElVisible && scroll <= (el.offsetTop + el.offsetHeight)

#         if isElVisible
#             bgOffset = offsetY - el.offsetTop
#             el.style.backgroundPositionY = "#{bgOffset}px"

# window.onscroll = ->
#     initParallaxEffect()

# window.onscroll()