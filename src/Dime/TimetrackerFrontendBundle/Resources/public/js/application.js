/*
 * application.js
 *
 */

(function ($) {

  $.fn.form = function() {
    var $form = $(this),
        prefix = $form.data('prefix') || '';

    return {
      data: function() {
        var data = {};
        if ($form) {
          $(':input', $form).each(function(idx, el) {
            var $el = $(el);
            if (el.id && el.id.search(prefix) != -1) {
              data[el.id.replace(prefix, '')] = $el.val();
            }
          });
        }
        return data;
      },
      fill: function(data) {
        if ($form && data) {
          for (var name in data) if (data.hasOwnProperty(name)) {
            var input = $('#' + prefix + name, $form);
            if (input) {
              input.val(data[name]);
            }
          }
        }
      },
      clear: function() {
        $(':input', $form).each(function(idx, el) {
          var type = el.type;
          var tag = el.tagName.toLowerCase();
          if (type == 'text' || type == 'password' || tag == 'textarea')
            $(el).val("");
          else if (type == 'checkbox' || type == 'radio')
            el.checked = false;
          else if (tag == 'select')
            el.selectedIndex = -1;
        });
      }
    };
  };

  var Service = Backbone.Model.extend({
    urlRoot: 'api/services'
    
  });

  var Services = Backbone.Collection.extend({
    url: 'api/services',
    model: Service
  });

  var serviceListView = Backbone.View.extend({
    el: $('#services'),
    initialize: function() {
      _.bindAll(this);

      this.collection.bind('reset', this.addAll);
      this.collection.bind('add', this.addOne);
      this.collection.bind('change', this.change);
      this.collection.bind('destroy', this.destroy);

      this.form = new serviceForm({ el: $('#service-form') });
      this.form.collection = this.collection;
    },
    render: function() {
      return this;
    },
    addAll: function() {
      this.el.html('');
      this.collection.each(this.addOne);
    },
    addOne: function(item) {
      this.el.append(new serviceItemView({model: item, form: this.form}).render().el);
    },
    change: function() {
      // TODO replace more efficent
      this.addAll();
    },
    destroy: function() {
      // not needed at the moment
    }
  });

  var serviceItemView = Backbone.View.extend({
    tagName: 'div',
    template: _.template($('#tpl-service-item').html()),
    events: {
      'click .edit': 'edit',
      'click .delete': 'clear'
    },
    initialize: function(obj) {
      _.bindAll(this);
      if (obj && obj.form) {
        this.form = obj.form;
      }
      this.model.bind('destroy', this.remove, this);
    },
    render: function() {
      $(this.el).html(this.template(this.model.toJSON()));
      $(this.el).attr('id', 'service-' + this.model.get('id'));
      return this;
    },
    edit: function() {
      this.form.model = this.model;
      this.form.render();
    },
    remove: function() {
      $(this.el).remove();
    },
    clear: function() {
      this.model.destroy();
    }
  });

  var serviceForm = Backbone.View.extend({
    events: {
      'click .save': 'save',
      'click .cancel': 'close'
    },
    initialize: function() {
        _.bindAll(this);
        this.form = this.el.form();
    },
    render: function() {
        this.form.clear();
        this.form.fill(this.model.toJSON());
        this.el.modal({backdrop: 'static', show: true});
        return this;
    },
    save: function() {
      if (this.model) {
        if (this.model.isNew()) {
          this.model.set(this.form.data());
          if (this.collection) {
            this.collection.create(this.model, {success: this.close});
          }
        } else {
          this.model.save(this.form.data(), {success: this.close});
        }
      }
    },
    close: function() {
        this.el.data('modal').hide();
    }
  });

  var services = new Services({form: new serviceForm({ el: $('#service-form') })});
  new serviceListView({collection: services}).render();
  services.fetch();

  // TODO Use backbone routes - Problem: howto navigate back from #new ?
  $('.service-new').bind('click', function(e) {
    e.preventDefault();
    var form = new serviceForm({ el: $('#service-form') });
    form.collection = services;
    form.model = new Service();
    form.render();
  });


  /*var ServiceRouter = Backbone.Router.extend({
    routes: {
      'new': 'show'
    },
    show: function() {
      var form = new serviceForm({ el: $('#service-form') });
      form.collection = services;
      form.model = new Service();
      form.render();
    }
  });
  var service_routes = new ServiceRouter();
  Backbone.history.start();*/

  /*
  $(document).ready(function() {

   /*$('nav').dropdown();

    $('table#dataTable').button();

    $('.alert-message').alert();

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
  });	*/
})(jQuery);
