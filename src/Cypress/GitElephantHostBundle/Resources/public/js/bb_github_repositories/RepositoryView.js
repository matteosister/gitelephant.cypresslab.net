/**
 * User: matteo
 * Date: 30/12/12
 * Time: 23.09
 * @matteosister
 * Just for fun...
 */


var RepositoryView = Backbone.View.extend({
    tagName: 'tr',
    className: 'github-repo',
    events: {
        "click a.clone-repo": "cloneRepo"
    },
    initialize: function() {
        this.render();
    },
    render: function() {
        var template = _.template($('#repository_view_tpl').html(), { model: this.model });
        this.$el.html(template);
        this.$el.fadeIn();
    },
    cloneRepo: function(evt) {
        this.addSpinner();
        if (this.$el.find('a').hasClass('disabled')) {
            return false;
        }
        this.$el.find('a').removeClass('btn-primary').addClass('btn-info');
        this.$el.find('a').html('view');
        this.$el.find('a').hide();
        $.ajax({
            context: this,
            type: "POST",
            url: $(evt.target).attr('href'),
            data: this.model.toJSON(),
            success: function() {
                this.removeSpinner();
            },
            dataType: 'html'
        });
        return false;
    },
    addSpinner: function() {
        this.$el.find('td:first').spin(spinnerOptsSmall).children('a').hide();
    },
    removeSpinner: function() {
        this.$el.find('td:first').spin(false).children('a').show();
    }
});