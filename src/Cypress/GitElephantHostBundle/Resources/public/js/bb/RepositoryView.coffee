###
User: matteo
Date: 01/12/12
Time: 12.43
@matteosister
Just for fun...
###
class window.RepositoryView extends Backbone.View
  initialize: ->
    @breadcrumbView = new BreadcrumbView(el: $("div.bc"))
    @commitCollection = new CommitCollection()
    @commitCollection.bind "commitsLoaded", @commitsLoaded, this
    @$el.addClass("actual").children("section").addClass "actual"

    #.css('position', 'absolute');
    @loadCommits()

  events:
    "click a.tree-object": "loadRoute"

  commitsLoaded: (commits) ->
    @getSpinnerCommitsDomObject().spin false
    _.each @$el.find("section.actual").find("tr:not(.back)"), ((elm) ->
      commit = @commitCollection.getCommit($(elm).data().path)
      $(elm).find("td:nth(1)").html "<a href=\"" + commit.get("url") + "\" title=\"" + commit.get("author_name") + "\">" + "<img src=\"http://www.gravatar.com/avatar/" + md5(commit.get("author_email")) + "?s=20&d=mm\" />" + commit.get("message") + "</a>"
    ), this

  loadRoute: (evt, forward) ->
    if typeof forward is "undefined"
      @isForward = not $(evt.target).hasClass("back")
    else
      @isForward = forward
    @loading()
    app_router.navigate $(evt.target).attr("href"), true
    false

  loadContent: (routeData) ->
    routeData.path = decodeURI(routeData.path)
    url = Routing.generate("ajax_tree_object", routeData)

    # new section
    newTable = @sectionExists(routeData.path)
    from = (if @isForward then "100%" else "-100%")
    to = "0"
    if newTable.length > 0
      newTable.removeClass("remove").addClass "actual"

      #.css('position', 'absolute')
      @$el.children("section.actual").css("left", from).show().animate
        left: to
      , (if @sectionCount() is 1 then 0 else 400)
      @breadcrumbView.loadContent routeData
      @loadCommits()
    else
      $.ajax
        url: url
        context: this
        success: (data) ->
          @removeSpinner()
          @$el.append data
          @$el.children("section:not(.remove)").addClass "actual"
          @$el.find("section.actual").data "path",
            path: routeData.path


          #.css('position', 'absolute')
          @$el.children("section.actual").css("left", from).animate
            left: to
          , 0
          @breadcrumbView.loadContent routeData
          @loadCommits()

        error: ->
          @removeSpinner()


  addErrorSection: ->

  sectionExists: (path) ->
    @$el.find "section[data-path=\"" + path + "\"]"

  sectionCount: ->
    @$el.find("section").length

  loading: ->

    # old section
    from = "0"
    to = (if @isForward then "-100%" else "100%")
    @$el.children("section:not(.remove)").addClass("remove").removeClass("actual").css("position", "absolute").css("left", from).animate
      left: to
    , 400, $.proxy(@resetView, this)

  resetView: ->
    @removeTable()
    @addSpinner()  if @$el.find("section:not(.remove)").length is 0

  loadCommits: ->
    commits = []
    @$el.find("section.actual tr.git").each ->
      data = $(this).data()
      commits.push data  unless "content" is data.type

    return if 0 is commits.length
    if @breadcrumbView.getPath() is '/'
      url = Routing.generate("commits_root_info",
        slug: @$el.data().slug
        ref: @$el.data().ref
      )
    else
      url = Routing.generate("commits_info",
        slug: @$el.data().slug
        path: @breadcrumbView.getPath()
        ref: @$el.data().ref
      )
    if @commitCollection.addCommits(url, commits)
      @getSpinnerCommitsDomObject().spin spinnerOptsSmall
    else
      @commitsLoaded()

  getSpinnerCommitsDomObject: ->
    @$el.find("section.actual").find("tbody tr:not(.back):not(.blob)").first().find "td:nth(1)"

  addSpinner: ->
    @$el.css "height", "200px"
    @$el.spin spinnerOpts

  removeSpinner: ->
    @$el.spin false

  removeTable: ->
    @$el.find("section.remove").hide()