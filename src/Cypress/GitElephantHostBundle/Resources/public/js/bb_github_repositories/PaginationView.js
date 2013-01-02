/**
 * User: matteo
 * Date: 31/12/12
 * Time: 14.22
 * @matteosister
 * Just for fun...
 */

var PaginationView = Backbone.View.extend({
    tagName: 'div',
    className: 'pagination',
    events: {
        "click a": "clickLink"
    },
    clickLink: function(evt) {
        this.trigger('change_page', $(evt.target).attr('href'));
        return false;
    },
    initialize: function() {
        this.render();
        this.load();
    },
    load: function(url) {
        var callUrl = Routing.generate('github_repositories_pagination', {
            url: 1 == arguments.length ? url : null
        });
        $.ajax({
            url: callUrl,
            context: this,
            success: function(html) {
                this.$el.html(html);
            }
        });
    },
    addSpinner: function() {
        this.$el.css('min-height', '40px');
        this.$el.spin(spinnerOptsSmall);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    }
});