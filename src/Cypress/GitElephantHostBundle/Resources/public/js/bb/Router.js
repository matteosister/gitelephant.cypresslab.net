/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.19
 * @matteosister
 * Just for fun...
 */

var AppRouter = Backbone.Router.extend({
    routes: {
        ":controller/:slug/tree/:ref/*path": "treeObject",
        ":controller/:slug": "main"
    }
});

var commit_collection;

var app_router = new AppRouter;
Backbone.history.start({pushState: true});

app_router.on('route:treeObject', function (controller, slug, ref, path) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
        slug: slug,
        ref: ref,
        path: path
    }), path);
    breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', {
        slug: slug,
        ref: ref,
        path: path
    }));
});

app_router.on('route:main', function (controller, slug) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
        slug: slug
    }));
    breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', {
        slug: slug
    }));
});