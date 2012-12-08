/**
 * User: matteo
 * Date: 07/12/12
 * Time: 10.03
 * @matteosister
 * Just for fun...
 */

var CommitCollection = Backbone.Collection.extend({
    model: CommitModel,
    initialize: function(repositorySlug) {
        this.repositorySlug = repositorySlug;
    },
    addCommits: function(url, commits) {
        var newCommits = [];
        _.each(commits, function(obj, index) {
            if (!this.hasCommit(obj.path)) {
                newCommits[index] = obj;
            }
        }, this);
        if (newCommits.length > 0) {
            this.loadCommits(url, newCommits);
            return true;
        } else {
            return false;
        }
    },
    hasCommit: function(path) {
        var paths = [];
        this.each(function(model) {
            paths.push(model.get('path'));
        });
        return _.contains(paths, path);
    },
    getCommit: function(path) {
        return _.find(this.models, function(model) {
            return path == model.get('path');
        }, this);
    },
    loadCommits: function(url, commitsObjects) {
        $.ajax({
            context: this,
            url: url,
            dataType: 'json',
            type: 'POST',
            data : JSON.stringify(commitsObjects),
            contentType : 'application/json',
            success: function(commits) {
                _.each(commits, function(commit) {
                    var commitModel = new CommitModel(commit);
                    this.add(commitModel);
                }, this);
                this.trigger('commitsLoaded');
            }
        });
    }
});