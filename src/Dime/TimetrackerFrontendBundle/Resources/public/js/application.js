/*
 * application.js
 * 
 */
 
(function ($) {

  var Service = Backbone.Model.extend({
    urlRoot: 'api/services',
    url: function() {
      return this.urlRoot + '/' + this.id;
    }
  });
  
  var serviceList = Backbone.Collection.extend({
    url: 'api/services',
    model: Service
  });
  
  var list = new serviceList();
  
  var serviceView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#service-item').html()),
    events: {
      'click .service-edit': 'edit',
      'click .service-save': 'save',
      'click .service-delete': 'clear'
    },
    initialize: function() {
      this.model.bind('destroy', this.remove, this);
    },
    render: function() {
      $(this.el).html(this.template(this.model.toJSON()));
      return this;
    },
    edit: function() {
      var $edit = $('.edit', this.el);
      var $input_name = $('.service-name', $edit);
      $input_name.val(this.model.get('name'));
      $('.service-rate', $edit).val(this.model.get('rate'));
      $('.display', this.el).hide();
      $edit.show();
      $input_name.focus().select();
    },
    save: function() {
      var $edit = $('.edit', this.el);
      
      this.model.save({
        name: $('.service-name', $edit).val(),
        rate: $('.service-rate', $edit).val()
      });
      $('.display', this.el).show();
      $('.edit', this.el).hide();
    },
    remove: function() {
      $(this.el).remove();
    },
    clear: function() {
      this.model.destroy();
    }
  }); 
  
  
  var appView = Backbone.View.extend({
    initialize: function() {
      list.bind('add', this.addOne, this);
      list.bind('reset', this.addAll, this);
      list.bind('all', this.render, this);
      
      list.fetch();    
    },
    render: function() {
      
    },
    addOne: function(service) {
      var view = new serviceView({model: service});
      this.$('#services').append(view.render().el);
    },
    addAll: function() {
      list.each(this.addOne);
    }
  });
  
  var app = new appView();
  
  
  
/*
  $.getJSON('api/services.json', function(data) {
    $.each(data, function(idx, item) {
      var html = '';
      
      for (var name in item) if (item.hasOwnProperty(name)) {
        html += '<td>' + item[name] + '</td>'
      }
      
      html += '<td><a href="#' + item['id'] + '">edit</a></td>'
      
      $('.loading').spin(false);
      $('table#serviceTable tbody').append('<tr>' + html + '</tr>');
      
    });
  });
  */
  
  $(document).ready(function() {
    $('nav').dropdown();

    $('table#dataTable').button();

    $('.alert-message').alert();

    $('.data-edit').modal();

    $('.client-project').twipsy({
      live: true,
      delayIn: 3000
    });

    $('#magic-data-entry').popover({
      offset: 10,
      placement: 'below',
      delayIn: 3000,
      trigger: focus
    });

    //$('.loading').css({height: '50px', width: '100%'}).spin('large', 'black');
    
    //$('table.data-table').tablesorter({ sortList: [[1,0]] });
  });	
})(jQuery);
