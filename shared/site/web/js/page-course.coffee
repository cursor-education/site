#
app =
    init: ->
        app.initScrollHook()
        helpers.initScrollToTopEls()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

#
app.init()