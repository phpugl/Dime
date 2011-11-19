/*
 * application.js
 * 
 */
 
(function ($) {

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

    $('.loading').css({height: '50px', width: '100%'}).spin('large', 'black');
    
    $('table.data-table').tablesorter({ sortList: [[1,0]] });
  });	
})(jQuery);
