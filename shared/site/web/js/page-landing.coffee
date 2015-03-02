#
app =
    init: ->
        app.initScrollHook()
        helpers.initScrollToTopEls()
        helpers.initSubmitForm()
        # app.initGallery()

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

#
# Page infographic
#

IG = do ->
  ps = ps or {}

  ps.getEl = (id) ->
    document.getElementById id

  ps.current = ->
    ps.steps[0]

  ps.addClass = (id, cNm) ->
    ps.getEl(id).className = ' ' + cNm
    return

  ps.removeClasses = (id) ->
    ps.getEl(id).className = ''
    return

  {
    initialize: (id, steps) ->
      @id = id
      ps.steps = steps
      this
    showTitle: ->
      i = 1
      while i < ps.steps.length
        ps.removeClasses ps.steps[i]
        i++
      ps.addClass ps.current(), 'active'
      return
    switchSlides: ->
      ps.removeClasses @id
      ps.addClass @id, ps.current()
      return
    shiftSteps: ->
      ps.steps.push ps.steps.shift()
      return

  }
iG = IG.initialize('infographic', [
  'step1'
  'step2'
  'step3'
])
window.setInterval (->
  iG.switchSlides()
  iG.showTitle()
  iG.shiftSteps()
  return
), 3173