/**
 * User: matteo
 * Date: 07/12/12
 * Time: 10.03
 * @matteosister
 * Just for fun...
 */


var slug = $('.repository').data().slug;

var CommitCollection = Backbone.Collection.extend({
    model: CommitModel,
    initialize: function(repositorySlug) {
        this.repositorySlug = repositorySlug;
    },
    addCommits: function(commitsId) {
        commits = {};
        _.each(commitsId, function(obj, index) {
            var commitId = obj.commitid;
            if (!this.hasCommit(commitId)) {
                commits['commits[' + index + ']'] = commitId;
            }
        }, this);
        this.loadCommits(commits);
    },
    hasCommit: function(commitId) {
        var list = _.map(this.models, function(obj) {
            return obj.commitId;
        });
        return _.contains(list, commitId);
    },
    loadCommits: function(commits) {
        var url = Routing.generate('commits_info', {
            slug: this.repositorySlug
        });
        $.ajax({
            url: url,
            dataType: 'json',
            data: commits,
            type: 'POST',
            beforeSend: function(x) {
                if (x && x.overrideMimeType) {
                  x.overrideMimeType("application/j-son;charset=UTF-8");
                }
            }
        });
    }
});

commit_collection = new CommitCollection(slug);