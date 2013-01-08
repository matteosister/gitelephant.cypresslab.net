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
        if (this.$el.find('a').hasClass('disabled')) {
            return false;
        }
        this.$el.find('a').addClass('disabled');
        this.$el.find('a').removeClass('btn-primary');
        this.$el.find('a').html('cloning');
        $.post(
            $(evt.target).attr('href'),
            this.model.toJSON(),
            function(responseText) {
                //console.log(responseText);
            },
            'html'
        );
        return false;
    }
});