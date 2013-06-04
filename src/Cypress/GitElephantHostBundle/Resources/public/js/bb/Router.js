/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.19
 * @matteosister
 * Just for fun...
 */

var AppRouter = Backbone.Router.extend({
    previousFragment: null,
    initialize: function(data) {
        console.log(data.repositoryView);
        if (typeof _gaq != 'undefined') {
            this.bind('all', this._trackPageView);
        };
        this.on('route', function(route, params) {
            console.log(route);
            //this.previousFragment = Backbone.history.getFragment();
        });
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
        _gaq.push(['_trackPageview', "/" + url]);
    }
});

