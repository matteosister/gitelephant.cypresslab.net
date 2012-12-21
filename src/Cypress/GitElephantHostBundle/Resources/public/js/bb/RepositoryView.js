/**
 * User: matteo
 * Date: 01/12/12
 * Time: 12.43
 * @matteosister
 * Just for fun...
 */

var RepositoryView = Backbone.View.extend({
    initialize: function() {
        this.breadcrumbView = new BreadcrumbView({ el: $('div.bc') });
        this.commitCollection = new CommitCollection();
        this.commitCollection.bind('commitsLoaded', this.commitsLoaded, this);
        this.$el
            .addClass('actual')
            .children('section')
            .addClass('actual')
            .css('position', 'absolute');
        this.loadCommits();
    },
    events: {
        "click a.tree-object": "loadRoute"
    },
    commitsLoaded: function() {
        this.getSpinnerCommitsDomObject().spin(false);
        _.each(this.$el.find('section.actual').find('tr:not(.back)'), function(elm) {
            var commit = this.commitCollection.getCommit($(elm).data().path);
            $(elm).find('td:nth(1)').html('<a href="' + commit.get('url') + '">' + commit.get('message') + '</a>');
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
    loadContent: function(routeData) {
        var url = Routing.generate('ajax_tree_object', routeData);
        // new section
        var newTable = this.sectionExists(routeData.path);
        var from = this.isForward ? '100%' : '-100%';
        var to = '0';
        if (newTable.length > 0) {
            newTable.removeClass('remove').addClass('actual');
            this.$el.children('section.actual')
                .css('position', 'absolute')
                .css('left', from)
                .show()
                .animate({
                    'left': to
                }, this.sectionCount() == 1 ? 0 : 400);
            this.loadCommits();
            this.breadcrumbView.loadContent(routeData);
        } else {
            $.ajax({
                url: url,
                context: this,
                success: function(data) {
                    this.removeSpinner();
                    this.$el.append(data);
                    this.$el.children('section:not(.remove)')
                        .addClass('actual');
                    this.$el.find('section.actual').data('path', {path: routeData.path});
                    this.$el.children('section.actual')
                        .css('position', 'absolute')
                        .css('left', from)
                        .animate({
                            'left': to
                        }, 0);
                    this.loadCommits();
                    this.breadcrumbView.loadContent(routeData);
                }
            });
        }
    },
    sectionExists: function(path) {
        return this.$el.find('section[data-path="' + path + '"]');
    },
    sectionCount: function() {
        return this.$el.find('section').length;
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
        var url = Routing.generate('commits_info', {
            slug: this.$el.data().slug,
            ref: this.$el.data().ref
        });
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
    }
});

