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
    lastUrl: null,
    events: {
        "click a": "clickLink"
    },
    clickLink: function(evt) {
        if ($(evt.target).parent('li').hasClass('disabled')) {
            return false;
        }
        var url =  $(evt.target).attr('href');
        if ('#' == url) {
            return false;
        }
        this.$el.find('li').addClass('disabled');
        this.$el.find('ul').animate({
            opacity: 0.5
        });
        this.addSpinner();
        this.lastUrl = url;
        this.trigger('changePage', url);
        return false;
    },
    initialize: function() {
        this.render();
    },
    load: function(url) {
        this.addSpinner();
        var callUrl = Routing.generate('github_repositories_pagination', {
            url: this.lastUrl !== null ? this.lastUrl : url
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