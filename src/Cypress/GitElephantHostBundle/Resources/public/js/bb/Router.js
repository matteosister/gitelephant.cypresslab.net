/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.19
 * @matteosister
 * Just for fun...
 */

var AppRouter = Backbone.Router.extend({
    routes: {
        ":slug/tree/:ref/*path": "treeObject",
        ":controller/:slug/tree/:ref/*path": "treeObjectController",
        ":slug": "main",
        ":controller/:slug": "mainController"
    }
});

