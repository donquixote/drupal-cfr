(function($) {
  Drupal.behaviors.cfrapi_orderedIdsTabledrag = {

    attach: function (context, settings) {
      $('.cfrapi-tabledrag', context).once('tabledrag', function() {
        var tabledrag = new Drupal.tableDrag(this, {});
        // Suppress the changed warning.
        tabledrag.changed = true;
        // Remove the "Show row weights" control.
        $('> .tabledrag-toggle-weight-wrapper', this.parentNode).remove();
      });
    }
  };
})(jQuery);
