/**
 * User: matteo
 * Date: 13/12/12
 * Time: 16.57
 * @matteosister
 * Just for fun...
 */


var repository_view = new RepositoryView({ el: $('.repository') });
//var breadcrumb_view = new BreadcrumbView({ el: $('div.bc') });

var manageArguments = function(args) {
    var i = 0;
    if (args.length == 4) {
        i = 1;
    }
    if (args.length == 1) {
        return {
            slug: args[0]
        };
    }
    if (args.length == 2) {
        return {
            slug: args[1]
        };
    }
    return {
        slug: args[i],
        ref: args[i+1],
        path: args[i+2]
    };
};

var loadTreeObject = function() {
    var data = manageArguments(arguments);
    repository_view.loadContent(data);
    //breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', data));
};
var loadMain = function() {
    var data = manageArguments(arguments);
    data.path = '';
    repository_view.loadContent(data);
    //breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', data));
};
var loadTreeObjectMain = function() {
    i = 0;
    if (arguments.length == 3) {
        i = 1;
    }
    var data = {};
    data.slug = arguments[i];
    data.ref = arguments[i+1];
    data.path = '';
    repository_view.loadContent(data);
    //breadcrumb_view.loadContent(Routing.generate('ajax_breadcrumb', data));
};

var app_router = new AppRouter({ repositoryView: repository_view });
app_router.on('route:treeObject', loadTreeObject);
app_router.on('route:treeObjectController', loadTreeObject);
app_router.on('route:treeObjectMain', loadTreeObjectMain);
app_router.on('route:treeObjectMainController', loadTreeObjectMain);
app_router.on('route:main', function(slug) {
    loadMain(slug);
});
app_router.on('route:mainController', function(controller, slug) {
    loadMain(slug);
});

Backbone.history.start({
    pushState: true,
    root: "/"
});