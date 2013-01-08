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
        this.paginationView = new PaginationView();
        this.$el.append(this.paginationView.el);
        this.listView = new ListView();
        this.$el.append(this.listView.el);
        this.paginationView.bind('changePage', this.changePage, this);
        this.listView.bind('repositoriesLoaded', this.repositoriesLoaded, this);
    },
    changePage: function(url) {
        this.listView.load(url);
    },
    repositoriesLoaded: function() {
        this.paginationView.load();
    },
    render: function() {
        this.$el.prepend('<h1>Import your github repositories</h1>');
    }
});

var app_view = new AppView();
$('div.container-fluid').append(app_view.el);
