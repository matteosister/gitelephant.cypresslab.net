/**
 * User: matteo
 * Date: 01/12/12
 * Time: 12.43
 * @matteosister
 * Just for fun...
 */

var AppRouter = Backbone.Router.extend({
    routes: {
        ":controller/repo/:slug/tree/:ref/*path": "treeObject",
        ":controller/repo/:slug": "main"
    }
});

var app_router = new AppRouter;

var RepositoryView = Backbone.View.extend({
    events: {
        "click a.tree-object": "loadRoute"
    },
    loadRoute: function(evt) {
        this.loading();
        app_router.navigate($(evt.target).attr('href'), true);
        return false;
    },
    loadContent: function(url) {
        $.ajax({
            url: url,
            context: this,
            success: function(data) {
                this.finishLoading();
                this.$el.html(data);
            }
        });
    },
    loading: function() {
        this.$el.addClass('loading');
    },
    finishLoading: function() {
        this.$el.removeClass('loading');
    }
});

var repository_view = new RepositoryView({ el: $('.repository') });

app_router.on('route:treeObject', function (controller, slug, ref, path) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
            slug: slug,
            ref: ref,
            path: path
    }));
});

app_router.on('route:main', function (controller, slug) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
            slug: slug
    }));
});

Backbone.history.start({pushState: true, root: '/'});