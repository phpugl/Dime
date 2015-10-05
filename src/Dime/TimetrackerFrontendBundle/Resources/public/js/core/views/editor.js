'use strict';

/**
 * Dime - core/views/editor.js
 */
(function ($, Backbone, _, App) {

    // Create Content view in App.Views.Core
    App.provide('Views.Core.Editor', Backbone.View.extend({
        events: {
            'click .editable': 'click',
            'focus .editable': 'focus',
            'blur .editable': 'save',
            'keyup .editable': 'keyup'
        },
        render: function() {
            return this;
        },
        click: function(e) {
            if (e) {
                e.stopPropagation();
            }
        },
        focus:  function(e) {
            if (e) {
                var item = $(e.currentTarget),
                    key = item.data('editorModelKey');

                // load data from model
                item.text(this.model.get(key));
            }
            return this;
        },
        keyup:  function(e) {
            if (e) {
                var item = $(e.currentTarget);
                if (e.keyCode) {
                    // ESC cancel
                    if (e.keyCode == 27) {
                        item.data('editorCancel', 'true');
                        item.trigger('blur');
                    } else if (e.altKey && e.ctrlKey && e.keyCode == 83 ) { // ALT + CTRL + s
                        item.removeData('editorCancel');
                        item.trigger('blur');
                    }
                }
            }
            return this;
        },
        save: function(e) {
            if (e) {
                e.stopPropagation();
            }

            var item = $(e.currentTarget),
                key = '',
                data = {};

            if (item) {
                key = item.data('editorModelKey');

                if (item.data('editorCancel')) {
                    item.removeData('editorCancel');
                    item.html(this.model.get(key));
                    App.notify('Update canceled.', 'error');
                } else {
                    data[key] = App.Helper.Format.EditableText(item.html());
                    this.model.save(data, {
                        success: function(model) {
                            App.notify('Update successful.', 'success');
                        },
                        error: function(model, response) {
                            App.notify(response.status + ": " + response.statusText, "error");
                        }
                    });

                }
            }
            return this;
        }
    }));

})(jQuery, Backbone, _, Dime);
