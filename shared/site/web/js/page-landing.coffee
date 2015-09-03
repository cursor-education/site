#
app =
    init: ->
        app.initScrollHook()
        helpers.initScrollToTopEls()
        helpers.initSubmitForm()
        # app.initGallery()
        helpers.initAbTesting()
        helpers.initTooltip()
        app.initChoosingOfDirection();

    initScrollHook: ->
        window.onscroll = -> helpers.scrollHook()
        window.onscroll()

    initChoosingOfDirection: ->
        links = document.getElementsByClassName('directions-types')[0].getElementsByTagName('h2')
        $directionsLists = document.getElementsByClassName('direction-list')[0].getElementsByClassName('rows')
        
        if links.length > 1
            for link in links
                do (link) ->
                    link.onclick = (e) ->
                        e.preventDefault();

                        for activeLink in links
                            activeLink.classList.remove('active')

                        link.classList.add('active')

                        direction = link.getAttribute('data-direction')

                        for $directionList in $directionsLists
                            $directionList.classList.add('hidden')

                        $directionList = document.getElementById('direction-' + direction + '-list')
                        $directionList.classList.remove('hidden');

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