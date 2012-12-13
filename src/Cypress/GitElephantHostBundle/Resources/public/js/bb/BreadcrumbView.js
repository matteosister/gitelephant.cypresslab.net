/**
 * User: matteo
 * Date: 06/12/12
 * Time: 22.21
 * @matteosister
 * Just for fun...
 */

var BreadcrumbView = Backbone.View.extend({
    events: {
        "click a.bc": "loadRoute"
    },
    loadRoute: function(evt) {
        repository_view.loadRoute(evt, false);
        return false;
    },
    loadContent: function(url) {
        $.ajax({
            url: url,
            context: this,
            success: function(data) {
                this.$el.html(data);
            }
        });
    }
});