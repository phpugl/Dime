/*
 * jQuery - form
 *
 * handles form filling, clearing and data by input id
 * 
 * Usage:
 *
 * <form id="form-id" data-prefix="test-">
 *  <input id="test-id1" value="" >
 *  <input id="test-id2" value="" >
 * </form>
 *
 * var form = $('#form-id').form();
 * form.fill({id1: 'data1', id2: 'data2'});
 * form.data() -> return {id1: 'data1', id2: 'data2'}
 *
 * @package: Dime
 * @author: PHPUGL, http://phpugl.de
 */

(function ($) {

  // 
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
  
})(jQuery);
