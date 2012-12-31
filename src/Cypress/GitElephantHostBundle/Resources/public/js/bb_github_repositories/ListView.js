/**
 * User: matteo
 * Date: 30/12/12
 * Time: 22.14
 * @matteosister
 * Just for fun...
 */

var ListView = Backbone.View.extend({
    tagName: 'div',
    id: 'github-repository-list',
    githubRepositoryCollection: new GithubRepositoryCollection(),
    paginationView: new PaginationView(),
    render: function() {
        this.loadData();
        this.$el.append(this.paginationView.el);
    },
    initialize: function() {
        this.githubRepositoryCollection.bind('add', this.addRepository, this);
    },
    loadData: function() {
        this.addSpinner();
        $.ajax({
            url: Routing.generate('github_repositories', {'_format': 'json'}),
            context: this,
            success: function(data) {
                this.removeSpinner();
                var models = [];
                _.each(data, function (json) {
                    models.push(new GithubRepositoryModel(json));
                });
                this.githubRepositoryCollection.add(models);
            }
        });
    },
    addRepository: function(model) {
        this.$el.prepend(new RepositoryView({model: model}).el);
    },
    addSpinner: function() {
        this.$el.css('min-height', '40px');
        this.$el.spin(spinnerOpts);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    }
});

var list_view = new ListView();
$('div.container-fluid').append(list_view.el);
list_view.render();