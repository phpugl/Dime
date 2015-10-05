'use strict';

/**
 * Dime - core/helper/ui.js
 */
(function (App, $, _) {

    /**
     * Dime.Helper.UI.Form.Bind
     *
     * @param $form jQuery container with input elements
     * @param data form data object
     * @param ignore object with name to ignore
     * @return obj
     */
    App.provide('Helper.UI.Form.Bind', function($form, data, ignore) {
        ignore = ignore || {};

        $(':input[name]', $form).each(function (idx, input) {
            var $input = $(input),
                name = input.name;

            if (name && !ignore[name]) {
                var parts = name.split('-'),
                    value = App.Helper.Object.Get(data, parts);

                if (value !== undefined) {
                    switch (input.type) {
                        case 'checkbox':
                            input.checked = value;
                            break;
                        case 'radio':
                            input.checked = ($input.val() == value);
                            break;
                        default:
                            $input.val(value);
                    }
                    $input.trigger('change');
                }
            }
        });
    });

    /**
     * Dime.Helper.UI.Form.Error.Bind
     *
     * @param $form jQuery container with input elements
     * @param errors object with name: message
     * @return obj
     */
    App.provide('Helper.UI.Form.Error.Bind', function($form, errors) {
        $('.control-group', $form).removeClass('error');

        if (errors) {
            // Grep inputs with name
            $(':input[name]', $form).each(function (idx, input) {
                var $input = $(input),
                    name = input.name.replace(/\[\]/g,'');

                if (name && errors[name]) {
                    $('.error-' + name, $form).text(errors[name]);

                    var group = $input.parents('.control-group');
                    if (group.length > 0) {
                        group.addClass('error');
                    }
                }
            });
        }
    });

    /**
     * Dime.Helper.UI.Form.Error.Clear
     *
     * Remove errors from from
     *
     * @param $form jQuery container with input elements
     * @return obj
     */
    App.provide('Helper.UI.Form.Error.Clear', function($form) {
        $('.control-group', $form).removeClass('error');

        $(':input[name]', $form).each(function (idx, input) {
            var $input = $(input),
                name = input.name.replace(/\[\]/g,'');
            $('.error-' + name, $form).text('');
        });
    });


        /**
     * Dime.Helper.UI.Form.Clear
     *
     * @param $form jQuery container with input elements
     * @param ignore object with name to ignore
     * @return obj
     */
    App.provide('Helper.UI.Form.Clear', function($form, ignore) {
        ignore = ignore || {};

        $(':input', this.$el).each(function (idx, input) {
            var $input = $(input),
                name = input.name || input.id;

            if (name && !ignore[name]) {
                if (input.type && input.type == 'checkbox' || input.type == 'radio') {
                    input.checked = false;
                } else if (input.tag == 'select') {
                    input.selectedIndex = -1;
                } else {
                    $input.val(undefined);
                }
            }
        });
    });

    /**
     * Dime.Helper.UI.Form.Serialize
     *
     * @param $form jQuery container with input elements
     * @param ignore object with name to ignore
     * @param withoutEmpty
     * @return obj
     */
    App.provide('Helper.UI.Form.Serialize', function($form, ignore, withoutEmpty) {
        ignore = ignore || {};
        var data = {},
            component = {};

        // Grep inputs with name
        $(':input[name]', $form).each(function (idx, input) {
            var $input = $(input),
                name = input.name;

            if (name) {
                if (component[name]) {
                    return;
                }

                var val = $input.val(),
                    parts = name.split('-');

                switch(input.type) {
                    case 'checkbox':
                        if (input.checked) {
                            if (val === undefined || _.isEmpty(val)) {
                                val = true;
                            }
                        } else {
                            val = undefined;
                        }
                        break;
                    case 'radio':
                        if (input.checked) {
                            component[name] = true;
                            if (val === undefined || _.isEmpty(val)) {
                                val = true;
                            }
                        }
                        break;
                    case 'button': // button values are not intresting
                        return;
                }

                if (withoutEmpty) {
                    if (val === undefined || _.isEmpty(val)) {
                        return;
                    }
                }

                App.Helper.Object.Set(data, parts, val);
            }
        });

        return data;
    });

})(Dime, jQuery, _);
