#
app =
    init: ->
        app.initScrollHook()
        helpers.initTooltip()
        helpers.initScrollToTopEls()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

#
app.init()