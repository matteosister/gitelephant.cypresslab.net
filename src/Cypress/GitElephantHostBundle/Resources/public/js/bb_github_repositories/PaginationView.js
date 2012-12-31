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
    initialize: function() {
        this.render();
        this.load();
    },
    load: function() {
        $.ajax({
            url: Routing.generate('github_repositories_pagination'),
            context: this,
            success: function(html) {
                this.$el.append(html);
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