/**
 * User: matteo
 * Date: 30/12/12
 * Time: 22.14
 * @matteosister
 * Just for fun...
 */

var ListView = Backbone.View.extend({
    tagName: 'table',
    id: 'list',
    className: 'table table-striped table-hover',
    githubRepositoryCollection: null,
    userModel: null,
    apiModel: null,
    initialize: function() {
        this.render();
        this.loadUser();
        this.githubRepositoryCollection = new GithubRepositoryCollection();
        this.githubRepositoryCollection.bind('add', this.addRepository, this);
        this.githubRepositoryCollection.bind('reset', this.resetListHtml, this);
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
        this.apiModel.bind('api_loaded', this.apiLoaded, this);
    },
    apiLoaded: function() {
        this.loadData();
    },
    loadData: function() {
        this.load(Routing.generate('github_repositories', {'_format': 'json'}));
    },
    load: function(url) {
        this.addSpinner();
        $.ajax({
            url: url,
            context: this,
            dataType: 'json',
            success: function(data) {
                this.removeSpinner();
                this.githubRepositoryCollection.reset();
                this.githubRepositoryCollection.add(data);
            }
        });
    },
    addRepository: function(model) {
        this.$el.append(new RepositoryView({model: model}).el);
    },
    resetListHtml: function() {
        this.$el.html('');
    },
    addSpinner: function() {
        this.$el.css('min-height', '40px');
        this.$el.spin(spinnerOpts);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    }
});