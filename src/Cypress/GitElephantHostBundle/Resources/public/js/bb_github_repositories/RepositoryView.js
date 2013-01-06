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
        $.post(
            $(evt.target).attr('href'),
            this.model.toJSON(),
            function(responseText) {
                console.log(responseText);
            },
            'html'
        );
        return false;
    }
});