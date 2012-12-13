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
            .children('section')
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
        _.each(this.$el.find('section.actual').find('tr:not(.back)'), function(elm) {
            var commit = this.commitCollection.getCommit($(elm).data().path);
            $(elm).find('td:nth(1)').html(commit.get('message'));
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
        // new section
        var newTable = this.sectionExists(path);
        var from = this.isForward ? '100%' : '-100%';
        var to = '0';
        if (typeof newTable != 'undefined') {
            $(newTable).removeClass('remove').addClass('actual');
            this.$el.children('section.actual')
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
                    this.$el.children('section:not(.remove)')
                        .addClass('actual');
                    //jQuery.data(this.$el.find('section.actual'), 'path', {path: path});
                    this.$el.find('section.actual').data('path', {path: path});
                    this.finishLoading();
                    this.$el.children('section.actual')
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
    sectionExists: function(path) {
        var section = _.find(this.$el.find('section.remove'), function(section) {
            var data = $(section).data('path');
            if (null != data) {
                return data.path == path;
            }
            return false;
        }, this);
        return section;
    },
    loading: function() {
        // old section
        var from = '0';
        var to = this.isForward ? '-100%' : '100%';
        this.$el.children('section:not(.remove)')
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
        if (this.$el.find('section:not(.remove)').length == 0) {
            this.addSpinner();
        }
    },
    loadCommits: function() {
        var commits = [];
        this.$el.find('section.actual tr.git').each(function() {
            var data = $(this).data();
            if ('content' != data.type) {
                commits.push(data);
            }
        });
        if (0 == commits.length) {
            return;
        }
        var url = Routing.generate('commits_info', { slug: this.$el.data().slug });
        if (this.commitCollection.addCommits(url, commits)) {
            this.getSpinnerCommitsDomObject().spin(spinnerOptsSmall);
        } else {
            this.commitsLoaded();
        }
    },
    getSpinnerCommitsDomObject: function() {
        return this.$el.find('section.actual').find('tbody tr:not(.back):not(.blob)').first().find('td:nth(1)');
    },
    addSpinner: function() {
        this.$el.css('height', '200px');
        this.$el.spin(spinnerOpts);
    },
    removeSpinner: function() {
        this.$el.spin(false);
    },
    removeTable: function() {
        this.$el.find('section.remove').hide();
    },
    adjustHeight: function() {
        this.$el.css('height', this.$el.find('section.actual').innerHeight());
    },
    finishLoading: function() {
        this.adjustHeight();
    }
});

