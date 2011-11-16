/*
 * application.js
 * 
 */
 
(function ($) {
    
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

          $('table#dataTable').tablesorter({ sortList: [[1,0]] });
      });
	
})(window.jQuery || window.ender);
