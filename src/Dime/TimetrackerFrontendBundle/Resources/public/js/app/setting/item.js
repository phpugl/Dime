'use strict';

/**
 * Dime - views/setting.item.js
 */
(function ($, App) {

  // Create item view in App.Views.Setting
  App.provide('Views.Setting.Item', App.Views.Core.ListItem.extend({
    events: {
      'click .edit': 'edit',
      'click .delete': 'delete'
    },
    edit: function(e) {
        e.stopPropagation();
    },
    'delete': function(e) {
      e.preventDefault();
      e.stopPropagation();

      this.model.bind('destroy', this.remove, this);

      // confirm destroy action
      if (confirm("Are you sure?")) {
        this.model.destroy({wait: true});
      }
    }
  }));
})(jQuery, Dime);
