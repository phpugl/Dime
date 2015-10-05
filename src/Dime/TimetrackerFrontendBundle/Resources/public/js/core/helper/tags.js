'use strict';

/**
 * Dime - core/helper/tags.js
 */
(function (App, $, _) {

    /**
     * Dime.Helper.Tags.Split
     *
     * @param tags a string or array
     * @param delimiert string, default: ' '
     * return object { data: [], add: [], remove: [] }
     */
    App.provide('Helper.Tags.Split', function (tags, delimiter) {
        var result = { data:[], add:[], remove:[] };
        delimiter = delimiter || ' ';
        if (_.isString(tags)) {
            result.data = tags.split(delimiter)
        } else if (_.isArray(tags)) {
            result.data = tags;
        } else {
            App.throw('Parameter "tags" is not a string or an array.', 'Helper.Tags.Split');
        }

        result.data = _.compact(result.data);
        for (var i = 0; i < result.data.length; i++) {
            // remove "+" and "-"
            var tagname = result.data[i].replace(/^[+-]/, '');
            if (result.data[i].search(/^-/) == -1) {
                result.add.push(tagname);
            } else {
                result.remove.push(tagname);
            }
        }

        return result;
    });

    /**
     * Dime.Helper.Tags.Update
     * Update a model with "tags" relation.
     *
     * @param model with tags relation
     * @param tags object, string, array
     */
    App.provide('Helper.Tags.Update', function (model, tags) {
        if (model && tags) {
            if (!tags.add && !tags.remove) {
                try {
                    tags = App.Helper.Tags.Split(tags);
                } catch (e) {
                    App.throw('Parameter "tags" is not a object, string or an array.', 'Helper.Tags.Update');
                }
            }

            if (model.hasRelation('tags')) {
                var modelTags = model.getRelation('tags');

                tags.add = _.compact(tags.add);
                if (modelTags && modelTags.length > 0) {
                    model.save({ tags: _.union(tags.add, modelTags.tagArray(tags.remove)) });
                } else {
                    model.save({ tags: tags.add });
                }
            }
        }
    });


})(Dime, jQuery, _);
