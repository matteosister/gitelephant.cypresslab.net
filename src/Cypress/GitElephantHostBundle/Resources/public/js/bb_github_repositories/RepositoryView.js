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
    initialize: function() {
        this.render();
    },
    render: function() {
        var template = _.template($('#repository_view_tpl').html(), { model: this.model });
        this.$el.html(template);
        this.$el.fadeIn();
    }
});