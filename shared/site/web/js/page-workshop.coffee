#
app =
    init: ->
        app.initScrollHook()
        helpers.initTooltip()
        helpers.initSubmitForm()
        helpers.initTooltip()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

#
app.init()