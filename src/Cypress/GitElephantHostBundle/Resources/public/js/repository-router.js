/**
 * User: matteo
 * Date: 01/12/12
 * Time: 12.43
 * @matteosister
 * Just for fun...
 */

var spinnerOpts = {
  lines: 13, // The number of lines to draw
  length: 7, // The length of each line
  width: 4, // The line thickness
  radius: 10, // The radius of the inner circle
  corners: 1, // Corner roundness (0..1)
  rotate: 0, // The rotation offset
  color: '#000', // #rgb or #rrggbb
  speed: 1, // Rounds per second
  trail: 60, // Afterglow percentage
  shadow: false, // Whether to render a shadow
  hwaccel: false, // Whether to use hardware acceleration
  className: 'spinner', // The CSS class to assign to the spinner
  zIndex: 2e9, // The z-index (defaults to 2000000000)
  top: 'auto', // Top position relative to parent in px
  left: 'auto' // Left position relative to parent in px
};

var AppRouter = Backbone.Router.extend({
    routes: {
        ":controller/repo/:slug/tree/:ref/*path": "treeObject",
        ":controller/repo/:slug": "main"
    }
});

var app_router = new AppRouter;
Backbone.history.start({pushState: true});

var RepositoryView = Backbone.View.extend({
    initialize: function() {
        this.$el
            .css('overflow', 'hidden')
            .css('position', 'relative');
    },
    events: {
        "click a.tree-object": "loadRoute"
    },
    loadRoute: function(evt) {
        this.isForward = !$(evt.target).hasClass('back');
        this.loading();
        app_router.navigate($(evt.target).attr('href'), true);
        return false;
    },
    loadContent: function(url, path) {
        // new table
        var newTable = this.tableExists(path);
        var from = this.isForward ? '100%' : '-100%';
        var to = '0';
        if (typeof newTable != 'undefined') {
            console.log('si');
            $(newTable).removeClass('remove').addClass('actual');
            this.$el.children('table.actual')
                .css('position', 'absolute')
                .css('left', from)
                .show()
                .animate({
                    'left': to
                }, 400);
        } else {
            $.ajax({
                url: url,
                context: this,
                success: function(data) {
                    this.removeSpinner();
                    this.$el.append(data);
                    this.$el.children('table:not(.remove)')
                        .addClass('actual');
                    //jQuery.data(this.$el.find('table.actual'), 'path', {path: path});
                    this.$el.find('table.actual').data('path', {path: path});
                    this.finishLoading();
                    this.$el.children('table.actual')
                        .css('position', 'absolute')
                        .css('left', from)
                        .animate({
                            'left': to
                        }, 200);
                }
            });
        }
    },
    tableExists: function(path) {
        var table = _.find(this.$el.find('table.remove'), function(table) {
            var data = $(table).data('path');
            if (null != data) {
                return data.path == path;
            }
            return false;
        }, this);
        return table;
    },
    loading: function() {
        // old table
        var from = '0';
        var to = this.isForward ? '-100%' : '100%';
        this.$el.children('table:not(.remove)')
            .addClass('remove')
            .removeClass('actual')
            .css('position', 'absolute')
            .css('left', from)
            .animate({
                'left': to
            }, 400, $.proxy(this.resetView, this));
    },
    resetView: function() {
        this.removeTable();
        if (this.$el.find('table:not(.remove)').length == 0) {
            this.addSpinner();
        }
    },
    addSpinner: function() {
        this.$el.css('height', '200px');
        this.$el.spin(spinnerOpts);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    },
    removeTable: function() {
        this.$el.find('table.remove').hide();
    },
    adjustHeight: function() {
        this.$el.css('height', this.$el.find('table.actual').outerHeight());
    },
    finishLoading: function() {
        this.adjustHeight();
    }
});

var repository_view = new RepositoryView({ el: $('.repository') });

app_router.on('route:treeObject', function (controller, slug, ref, path) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
        slug: slug,
        ref: ref,
        path: path
    }), path);
});

app_router.on('route:main', function (controller, slug) {
    repository_view.loadContent(Routing.generate('ajax_tree_object', {
        slug: slug
    }));
});