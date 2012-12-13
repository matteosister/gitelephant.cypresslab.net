/**
 * User: matteo
 * Date: 13/12/12
 * Time: 16.57
 * @matteosister
 * Just for fun...
 */


var repository_view = new RepositoryView({ el: $('.repository') });
var breadcrumb_view = new BreadcrumbView({ el: $('div.bc') });

var app_router = new AppRouter;
app_router.on('route:treeObject', function (slug, ref, path) {
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
app_router.on('route:main', function (slug) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
        slug: slug
    }));
    breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', {
        slug: slug
    }));
});

Backbone.history.start({
    pushState: true,
    root: "/"
});