'use strict';

/**
 * Dime - core/views/pager.js
 */
(function ($, Backbone, _, App) {

    /**
     * Dime.Views.Core.PagerItem
     *
     *
     */
    App.provide('Views.Core.PagerItem', Backbone.View.extend({
        tagName: 'li',
        template: '<a href="#"><%- text %></a>',
        events: {
            'click': 'update'
        },
        options: {
            text: '',
            current: false,
            pager: undefined
        },
        render:function () {
            var template = _.template(this.template);

            this.$el.html(template({ text: this.options.text }));

            if (this.options.current) {
                this.$el.addClass('active');
            }
            return this;
        },
        update:function(e) {
            if (e) {
                e.stopPropagation();
                e.preventDefault()
            }
            this.options.pager.setPage(this.options.text);
        }
    }));

    App.provide('Views.Core.PagerNext', App.Views.Core.PagerItem.extend({
        template: '<a href="#" title="<%- text %>"><i class="icon-chevron-right"></i></a>',
        render:function () {
            var template = _.template(this.template);
            this.$el.html(template({ text: this.options.text }));
            return this;
        },
        update:function(e) {
            if (e) {
                e.stopPropagation();
                e.preventDefault()
            }
            this.options.pager.nextPage();
        }
    }));

    App.provide('Views.Core.PagerPrev', App.Views.Core.PagerNext.extend({
        template: '<a href="#" title="<%- text %>"><i class="icon-chevron-left"></i></a>',
        update:function(e) {
            if (e) {
                e.stopPropagation();
                e.preventDefault()
            }
            this.options.pager.prevPage();
        }
    }));


    // provide list view in App.Views.Core
    App.provide('Views.Core.PagerList', Backbone.View.extend({
        tagName: 'ul',
        itemViews: [],
        render:function () {
            // clear list
            for (var i=0; i<this.itemViews.length; i++) {
                this.itemViews[i].remove();
            }
            this.itemViews = [];
            this.$el.html('');

            var pager = this.options.pager.getSettings();
            if (pager.prev) {
                this.addView(new App.Views.Core.PagerPrev({
                    pager: this.options.pager,
                    text: 'Prev'
                }));
            }

            if (pager.total > 1) {
                for (i=1; i<=pager.total; i++) {
                    this.addView(new App.Views.Core.PagerItem({
                        pager: this.options.pager,
                        text: i,
                        current: (i==pager.current)
                    }));
                }
            }

            if (pager.next) {
                this.addView(new App.Views.Core.PagerNext({
                    pager: this.options.pager,
                    text: 'Next'
                }));
            }

            return this;
        },
        addView: function(view) {
            this.itemViews.push(view);
            this.$el.append(view.render().el);
            return view;
        }
    }));

    App.provide('Views.Core.Pager', Backbone.View.extend({
        options: {
            requestTotal: 0,
            page: 1,
            count: 25
        },
        initialize:function () {
            if (this.collection) {
                this.collection.on('sync', this.retrievePagerHeader, this);
                this.collection.on('reset', this.render, this);
            }
        },
        render: function() {
            // render ul
            var list = new App.Views.Core.PagerList({
                pager: this
            }).render();

            // put list into element
            this.$el.html(list.el);

            this.collection.addFetchData('pager', this.getFetchData());

            return this;
        },
        remove: function() {
            if (this.collection) {
                this.collection.off();
            }
        },
        retrievePagerHeader: function(collection, xhr, options) {
            if(options && options.hasOwnProperty('getResponseHeader')) {
                options = {
                    xhr: options
                };
            }
            if(options && options.xhr.getResponseHeader('X-Pagination-Total-Results')) {
                this.options.requestTotal = options.xhr.getResponseHeader('X-Pagination-Total-Results');
                if (collection && collection.joinFetchDataCache) {
                    var opt = collection.joinFetchDataCache();
                    if (opt && opt.data && opt.data.limit) {
                        this.options.count = opt.data.limit;
                    }
                }

                this.render();
            }
        },
        getSettings: function() {
            return {
                current: this.options.page,
                prev: (this.options.page > 1),
                next: (this.options.page < this.pageCount()),
                total: this.pageCount()
            };
        },
        getFetchData:function () {
            var offset = (this.options.page - 1) * this.options.count;
            return {
                limit: this.options.count,
                offset: (offset) ? offset : 0
            };
        },
        pageCount:function () {
            return (this.options.count > 0) ? Math.ceil(this.options.requestTotal / this.options.count) : 0;
        },
        prevPage:function () {
            if (this.options.page > 0) {
                this.options.page -= 1;
            } else {
                this.options.page = 1;
            }
            this.collection.addFetchData('pager', this.getFetchData());
            this.collection.load();
        },
        setPage:function (num) {
            num = num || 1;
            if (num >= 1 && num <= this.pageCount()) {
                this.options.page = num;
            } else {
                this.options.page = 1;
            }
            this.collection.addFetchData('pager', this.getFetchData());
            this.collection.load();
        },
        nextPage:function() {
            if (this.options.page <= this.pageCount()) {
                this.options.page += 1;
            } else {
                this.options.page = this.pageCount();
            }
            this.collection.addFetchData('pager', this.getFetchData());
            this.collection.load();
        }
    }));


})(window.jQuery, window.Backbone, window._, window.Dime);
