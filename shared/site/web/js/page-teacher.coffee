#
app =
    init: ->
        app.initScrollHook()
        helpers.initTooltip()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

#
app.init()