/**
 * User: matteo
 * Date: 02/01/13
 * Time: 23.06
 * @matteosister
 * Just for fun...
 */

var AppView = Backbone.View.extend({
    tagName: 'div',
    id: 'github-repository-list',
    paginationView: null,
    listView: null,
    initialize: function() {
        this.render();
        console.log(this.$el);
        this.paginationView = new PaginationView();
        this.$el.append(this.paginationView.el);
        this.listView = new ListView();
        this.$el.append(this.listView.el);
        this.paginationView.bind('change_page', this.changePage, this);
    },
    changePage: function(url) {
        this.paginationView.load(url);
        this.listView.load(url);
    }
});

var app_view = new AppView();
$('div.container-fluid').append(app_view.el);
