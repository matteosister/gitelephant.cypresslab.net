/**
 * User: matteo
 * Date: 01/12/12
 * Time: 12.43
 * @matteosister
 * Just for fun...
 */

var RepositoryView = Backbone.View.extend({
    initialize: function() {
        this.commitCollection = new CommitCollection();
        this.commitCollection.bind('commitsLoaded', this.commitsLoaded, this);
        this.$el
            .css('overflow', 'hidden')
            .css('position', 'relative')
            .addClass('actual')
            .children('table')
            .addClass('actual')
            .css('position', 'absolute');
        this.adjustHeight();
        this.loadCommits();
    },
    events: {
        "click a.tree-object": "loadRoute"
    },
    commitsLoaded: function() {
        this.getSpinnerCommitsDomObject().spin(false);
        _.each(this.$el.find('table.actual').find('tr:not(.back)'), function(elm) {
            $(elm).find('td:nth(1)').html(this.commitCollection.getCommit($(elm).data().path).get('message'));
        }, this);
    },
    loadRoute: function(evt, forward) {
        if (typeof forward == 'undefined') {
            this.isForward = !$(evt.target).hasClass('back');
        } else {
            this.isForward = forward
        }
        this.loading();
        app_router.navigate($(evt.target).attr('href'), true);
        return false;
    },
    loadContent: function(url, path) {
        // new table
        var newTable = this.tableExists(path);
        var from = this.isForward ? '100%' : '-100%';
        var to = '0';
        if (typeof newTable != 'undefined') {
            $(newTable).removeClass('remove').addClass('actual');
            this.$el.children('table.actual')
                .css('position', 'absolute')
                .css('left', from)
                .show()
                .animate({
                    'left': to
                }, 400);
            this.adjustHeight();
            this.loadCommits();
        } else {
            $.ajax({
                url: url,
                context: this,
                success: function(data) {
                    this.removeSpinner();
                    this.$el.append(data);
                    this.$el.children('table:not(.remove)')
                        .addClass('actual');
                    //jQuery.data(this.$el.find('table.actual'), 'path', {path: path});
                    this.$el.find('table.actual').data('path', {path: path});
                    this.finishLoading();
                    this.$el.children('table.actual')
                        .css('position', 'absolute')
                        .css('left', from)
                        .animate({
                            'left': to
                        }, 200);
                    this.loadCommits();
                }
            });
        }
    },
    tableExists: function(path) {
        var table = _.find(this.$el.find('table.remove'), function(table) {
            var data = $(table).data('path');
            if (null != data) {
                return data.path == path;
            }
            return false;
        }, this);
        return table;
    },
    loading: function() {
        // old table
        var from = '0';
        var to = this.isForward ? '-100%' : '100%';
        this.$el.children('table:not(.remove)')
            .addClass('remove')
            .removeClass('actual')
            .css('position', 'absolute')
            .css('left', from)
            .animate({
                'left': to
            }, 400, $.proxy(this.resetView, this));
    },
    resetView: function() {
        this.removeTable();
        if (this.$el.find('table:not(.remove)').length == 0) {
            this.addSpinner();
        }
    },
    loadCommits: function() {
        var commits = [];
        this.$el.find('table.actual').find('tbody tr:not(.back)').each(function() {
            commits.push($(this).data());
        });
        var url = Routing.generate('commits_info', { slug: this.$el.data().slug });
        if (this.commitCollection.addCommits(url, commits)) {
            this.getSpinnerCommitsDomObject().spin(spinnerOptsSmall);
        } else {
            this.commitsLoaded();
        }
    },
    getSpinnerCommitsDomObject: function() {
        return this.$el.find('table.actual').find('tbody tr:not(.back):not(.blob)').first().find('td:nth(1)');
    },
    addSpinner: function() {
        this.$el.css('height', '200px');
        this.$el.spin(spinnerOpts);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    },
    removeTable: function() {
        this.$el.find('table.remove').hide();
    },
    adjustHeight: function() {
        this.$el.css('height', this.$el.find('table.actual').outerHeight());
    },
    finishLoading: function() {
        this.adjustHeight();
    }
});

var repository_view = new RepositoryView({ el: $('.repository') });
