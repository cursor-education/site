#
helpers =
    scrollTo: (val, duration, done) ->
        return if duration < 0
        return if window.scrollToWorking == true

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

        scrollToX = (element, x1, x2, t, v, step, operacion, done1) ->
            return done1() if t < 0 || t > 1 || v <= 0

            element.scrollTop = x1 - (x1-x2) * operacion(t);
            t += v * step

            setTimeout(->
                scrollToX element, x1, x2, t, v, step, operacion, done1
            , step)

        window.scrollToWorking = true

        scrollToX e, from, to, 0, 1/duration, 20, easeOutCuaic, ->
            window.scrollToWorking = false
            done()

    scrollHook: ->
        helpers.scrollHookHeader()
        helpers.scrollHookMenu()

    scrollHookHeader: ->
        scroll = document.body.scrollTop

        headerMenuEl = document.getElementById('header-menu')
        offsetTop = document.getElementById('learn-more-button').offsetTop

        className = 'open'
        classNameAnimate = headerMenuEl.getAttribute('data-animate-effect')

        if scroll >= offsetTop
            headerMenuEl.classList.add(className)
            headerMenuEl.classList.add(classNameAnimate)
        else
            headerMenuEl.classList.remove(className)
            headerMenuEl.classList.remove(classNameAnimate)

    scrollHookMenuReset: ->
        classSelected = 'selected'

        selectedEls = document.getElementById('menu').getElementsByClassName(classSelected)

        for selectedEl in selectedEls
            selectedEl.classList.remove(classSelected)

    scrollHookMenu: ->
        scroll = document.body.scrollTop

        helpers.scrollHookMenuReset()

        classSelected = 'selected'

        menuEls = document.getElementById('menu').getElementsByTagName('a')
        offsetTopMin = -100

        offsetMin = null
        selectMenuEl = null

        for menuEl in menuEls
            dataTopEl = menuEl.getAttribute('data-top')

            if dataTopEl
                topEl = document.getElementById(dataTopEl)
                topElOffset = scroll - (topEl.offsetTop + offsetTopMin)

                if topElOffset >= offsetTopMin
                    if offsetMin == null || offsetMin > Math.abs(topElOffset)
                        selectMenuEl = menuEl

        unless selectMenuEl?.classList.contains(classSelected)
            selectMenuEl?.classList.add(classSelected)

    parallax: ->
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

#
app =
    init: ->
        app.initScrollHook()
        app.initGoToTop()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

    initGoToTop: ->
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

                    helpers.scrollHookMenuReset()
                    el.classList.add('selected')

                    helpers.scrollTo topValue, 1000, ->
                        helpers.scrollHookMenuReset()
                        el.classList.add('selected')
#
app.init()