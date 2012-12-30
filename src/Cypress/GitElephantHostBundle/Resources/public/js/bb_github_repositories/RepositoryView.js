/**
 * User: matteo
 * Date: 30/12/12
 * Time: 23.09
 * @matteosister
 * Just for fun...
 */


var RepositoryView = Backbone.View.extend({
    tagName: 'div',
    className: 'github-repo',
    template: _.template('<strong><%= model.get("name") %></strong> <%= model.get("description") %>'),
    initialize: function() {
        this.$el.html(this.template({ model: this.model}));
    }
});