#
app =
    init: ->
        app.initScrollHook()
        helpers.initTooltip()
        helpers.initSubmitForm()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

#
app.init()