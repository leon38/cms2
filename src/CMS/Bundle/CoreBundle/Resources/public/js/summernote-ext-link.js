var current_context_media;
var range = '';
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
        'link-custom': function (context) {
            var self = this;
            // ui has renders to build ui elements.
            //  - you can create a button with `ui.button`
            var ui = $.summernote.ui;


            // add hello button
            context.memo('button.link-custom', function () {
                // create button
                var button = ui.button({
                    contents: '<i class="note-icon-link" />',
                    tooltip: 'Lien',
                    click: function (event, namespace, value) {
                        current_context_media = context;
                        range = current_context_media.invoke('editor.createRange');
                        current_context_media.invoke('editor.saveRange');
                        $('#modal-link-summernote').modal('show');
                    }
                });

                // create jQuery object from button instance.
                var $link = button.render();
                return $link;
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

                if ($('#modal-link-summernote').length == 0) {
                    this.$panel = $('<div class="modal fade" id="modal-link-summernote"><div class="modal-dialog"><div class="modal-content"><form method="post" id="link"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Lien</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Ajouter</button><button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button></div></div></div></div>').hide();
                    this.$panel.appendTo('body');
                    this.$panel.find('form').submit(this.insertLink);
                    $('#modal-link-summernote').on('show.bs.modal', function () {
                        var modal = $(this);
                        $.ajax({
                            url: '/admin/content/get-links',
                            type: 'get',
                            success: function (msg) {
                                modal.find('.modal-body').html(msg);
                            }
                        })
                    });
                }
            };

            this.insertLink = function () {


                if ($('#content-link').val() != "") {
                    current_context_media.invoke('editor.createLink', {
                        'text': range.toString(),
                        'url': $('#content-link').val(),
                        'newWindow': false
                    });
                    current_context_media.removeMemo('button.link');
                    $('#modal-link-summernote').modal('hide');
                } else if ($('#category-link').val() != "") {
                    current_context_media.invoke('editor.createLink', {
                        'text': range.toString(),
                        'url': $('#category-link').val(),
                        'newWindow': false
                    });
                    current_context_media.removeMemo('button.link');
                    $('#modal-link-summernote').modal('hide');
                } else if ($('#externe-link').val() != "") {
                    current_context_media.invoke('editor.restoreRange');
                    range = current_context_media.invoke('editor.createRange');
                    current_context_media.invoke('editor.createLink', {
                        'text': range.toString(),
                        'url': $('#externe-link').val(),
                        'isNewWindow': true
                    });
                    current_context_media.removeMemo('button.link');
                    $('#modal-link-summernote').modal('hide');
                }
                return false;
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
