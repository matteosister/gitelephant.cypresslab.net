/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.21
 * @matteosister
 * Just for fun...
 */

var BreadcrumbView = Backbone.View.extend({
    path: '/',
    initialize: function() {
        if (this.$el.children('ul').length > 0) {
            this.path = this.$el.children('ul').data().path
        }
        this.$el
            .find('ul.breadcrumb')
            .addClass('actual');
    },
    events: {
        "click a.bc": "loadRoute"
    },
    loadRoute: function(evt) {
        repository_view.loadRoute(evt, false);
        return false;
    },
    loadContent: function(routeData) {
        //console.log(routeData.path);
        this.path = routeData.path;
        var existentBreadcrumb = this.findByPath(routeData.path);
        if (existentBreadcrumb.length == 0) {
            this.ajaxRequest(routeData);
        } else {
            this.hideActual();
            existentBreadcrumb
                .removeClass('remove')
                .addClass('actual')
                .show();
        }

    },
    hideActual: function() {
        this.$el.find('ul.breadcrumb.actual')
            .removeClass('actual')
            .addClass('remove')
            .hide();
    },
    ajaxRequest: function(routeData) {
        $.ajax({
            url: Routing.generate('ajax_breadcrumb', routeData),
            context: this,
            success: function(data) {
                this.hideActual();
                this.$el.append(data);
                this.$el.find('ul:not(.remove)').addClass('actual');
            }
        });
    },
    findByPath: function(path) {
        return this.$el.find('ul[data-path="' + path + '"]');
    },
    getPath: function() {
        return this.path;
    }
});