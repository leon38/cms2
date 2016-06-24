var current_context_media;
(function (factory) {
  /* global define */
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function ($) {

  // Extends plugins for adding hello.
  //  - plugin is external module for customizing.
  $.extend($.summernote.plugins, {
    /**
     * @param {Object} context - context object has status of editor.
     */
    'media': function (context) {
      var self = this;
      // ui has renders to build ui elements.
      //  - you can create a button with `ui.button`
      var ui = $.summernote.ui;
      // add hello button
      context.memo('button.media', function () {
        // create button
        var button = ui.button({
          contents: '<i class="note-icon-picture" />',
          tooltip: 'Media',
          className: 'modal-media-summernote',
          click: function () {

            current_context_media = context;

            // Ouverture de la popup
            $('#modal-media-summernote').modal('show');
            // invoke insertText method with 'hello' on editor module.

            //current_context_media.invoke('editor.insertText', 'hello');
          }
        });

        // create jQuery object from button instance.
        var $media = button.render();
        return $media;
      });

      // This events will be attached when editor is initialized.
      this.events = {
        // This will be called after modules are initialized.
        'summernote.init': function (we, e) {

        },
        // This will be called when user releases a key on editable.
        'summernote.keyup': function (we, e) {

        }
      };

      // This method will be called when editor is initialized by $('..').summernote();
      // You can create elements for plugin
      this.initialize = function () {
        if ($('#modal-media-summernote').length == 0) {
          this.$panel = $('<div class="modal fade" id="modal-media-summernote"><div class="modal-dialog modal-large"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Médiathèque</h4></div><div class="modal-body clearfix"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div></div></div></div>').hide();

          this.$panel.appendTo('body');
        }
      };

      // This methods will be called when editor is destroyed by $('..').summernote('destroy');
      // You should remove elements on `initialize`.
      this.destroy = function () {
        this.$panel.remove();
        this.$panel = null;
      };
    }
  });
}));
