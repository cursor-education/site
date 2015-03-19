#
app =
    init: ->
        app.initScrollHook()
        helpers.initScrollToTopEls()
        helpers.initSubmitForm()
        # app.initGallery()
        helpers.initAbTesting()

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

    # initGallery: ->
    #     for el in document.getElementsByClassName('gallery')
    #         do (el) ->
    #             hiddenClass = 'hidden'

    #             elsClass = el.getAttribute('data-gallery-class')
    #             els = el.getElementsByClassName(elsClass)

    #             setInterval ->
    #                 num = parseInt(Math.random() * els.length)

    #                 for el in els
    #                     unless el.classList.contains(hiddenClass)
    #                         el.classList.add(hiddenClass)

    #                 els[num].classList.remove(hiddenClass)

    #                 console.log 11, el
    #             , 2000

#
app.init()