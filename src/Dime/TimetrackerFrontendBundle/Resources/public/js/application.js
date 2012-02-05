/*
 * application.js
 */
(function ($) {

  // Dime namespace
  window.dime = {
    collection: {},
    model: {},
    views: {}
  };

  $(document).ready(function() {
    $('.tabs').tabs();

    $('nav').dropdown();

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
  });
})(jQuery);
