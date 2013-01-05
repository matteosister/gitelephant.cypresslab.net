/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.19
 * @matteosister
 * Just for fun...
 */

var AppRouter = Backbone.Router.extend({
    initialize: function() {
        this.bind('all', this._trackPageView);
    },
    routes: {
        ":slug/tree/:ref/*path": "treeObject",
        ":controller/:slug/tree/:ref/*path": "treeObjectController",
        ":slug/tree/:ref": "treeObjectMain",
        ":controller/:slug/tree/:ref": "treeObjectMainController",
        ":slug": "main",
        ":controller/:slug": "mainController"
    },
    _trackPageView: function() {
        var url = Backbone.history.getFragment();
        _gaq.push(['_trackPageview', "/" + url])
    }
});

