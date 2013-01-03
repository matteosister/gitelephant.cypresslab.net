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
        var url =  $(evt.target).attr('href');
        if ('#' == url) {
            return false;
        }
        this.$el.find('li').addClass('disabled');
        this.$el.find('ul').animate({
            opacity: 0.5
        });
        this.addSpinner();
        this.trigger('change_page', url);
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
                this.removeSpinner();
                this.$el.html(html);
            }
        });
    },
    addSpinner: function() {
        this.$el.spin(spinnerOptsPagination);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    }
});