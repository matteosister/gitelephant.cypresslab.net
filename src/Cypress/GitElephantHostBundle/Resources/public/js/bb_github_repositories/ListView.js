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
    userModel: null,
    apiModel: null,
    render: function() {
        this.$el.prepend(this.paginationView.el);
        this.loadUser();
    },
    loadUser: function() {
        $.ajax({
            url: Routing.generate('github_user'),
            context: this,
            success: function(data) {
                this.userModel = new UserModel(data);
                this.loadApi();
            }
        });
    },
    loadApi: function() {
        this.apiModel = new ApiModel(this.userModel);
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
        this.$el.append(new RepositoryView({model: model}).el);
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