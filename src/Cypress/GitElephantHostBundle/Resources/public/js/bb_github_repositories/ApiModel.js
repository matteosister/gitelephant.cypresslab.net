/**
 * User: matteo
 * Date: 01/01/13
 * Time: 14.37
 * @matteosister
 * Just for fun...
 */

var ApiModel = Backbone.Model.extend({
    baseUrl: 'https://api.github.com/',
    userModel: null,
    callUrls: null,
    initialize: function(userModel) {
        this.userModel = userModel;
        this.loadBaseUrls();
    },
    loadBaseUrls: function() {
        var baseUrls = $.ajax({
            url: this.generateUrl(this.baseUrl),
            context: this,
            success: function(data) {
                this.callUrls = data;
                this.trigger('api_loaded');
            }
        });
    },
    generateUrl: function(url) {
        return url + '?access_token=' + this.userModel.get('access_token');
    }
});