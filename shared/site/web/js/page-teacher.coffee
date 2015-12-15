#
app =
    init: ->
        helpers.initMap()
        app.initScrollHook()
        helpers.initTooltip()
        app.initPageBackground()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

    initPageBackground: ->
        if teacher.background_image
            $el = document.getElementById('bg-image')
            $el.style.backgroundImage = 'url(' + teacher.background_image + ')'

#
app.init()