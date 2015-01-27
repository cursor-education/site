scrollToTop = (val) ->
    t = t || null
    val = val || 0

    if /^\d+$/.test(val)
        topValue = parseInt(val)
    else
        topValue = document.getElementById(val).offsetTop

    getScrollTop = ->
        document.body.scrollTop || document.documentElement.scrollTop

    isTopEqual = (val) ->
        getScrollTop() == val

    unless isTopEqual(topValue)
        per = 50

        sign = +1
        sign = -1 if getScrollTop() > topValue

        diff = (getScrollTop() - topValue) * -sign
        per = diff if diff > 0 and diff < per

        console.log getScrollTop(), topValue, sign, diff, per

        window.scrollBy 0, sign*per
        # t = setTimeout (-> scrollToTop(val)), 5
    else
        clearTimeout t

for el in document.getElementsByClassName('go-to-top')
    do (el) ->
        el.onclick = (e) ->
            e.preventDefault();
            scrollToTop el.getAttribute('data-top')

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