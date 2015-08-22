#
app =
    init: ->
        app.initScrollHook()
        helpers.initTooltip()
        app.initPageBackground()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

    initPageBackground: ->
        $body = document.getElementsByTagName('body')[0]

        $body.style.backgroundImage = 'url(' + teacher.background_image + ')'

#
app.init()