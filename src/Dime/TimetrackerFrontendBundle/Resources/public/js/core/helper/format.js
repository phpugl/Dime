'use strict';

/**
 * Dime - helper/format.js
 *
 * Register Activity model to namespace App.
 */
(function (App, $, moment) {

    /**
     * App.Helper.Format.Date
     *
     * @param date Date object, default: new Date
     * @param format default: 'YYYY-MM-DD HH:mm:ss'
     * @return {*}
     */
    App.provide('Helper.Format.Date', function(date, format) {
        date = date || new Date;
        format = format || 'YYYY-MM-DD HH:mm:ss';
        return moment(date).format(format);
    });

    /**
     * App.Helper.Format.Duration
     *
     * @param data, a duration in given unit, e.g. seconds
     * @param unit, default 'seconds' (look at moment.js)
     * @return string formatted as HH:mm:ss
     */
    App.provide('Helper.Format.Duration', function (data, unit) {
        if (data !== undefined && _.isNumber(data)) {
            unit = unit || 'seconds';
            var duration = moment.duration(data, unit);

            var hours = Math.floor(duration.asHours()),
                minute = duration.minutes(),
                second = duration.seconds();

            if (hours<10) {
                hours = '0' + hours;
            }
            if (minute<10) {
                minute = '0' + minute;
            }
            if (second<10) {
                second = '0' + second;
            }

            return [hours, minute, second].join(':');
        }
        return '';
    });

    /**
     * App.Helper.Format.EditableText
     * Transform HTML Content from "contenteditable" to Text with \n
     *
     * @param html html content
     * @return string sanitized text
     */
    App.provide('Helper.Format.EditableText', function(html) {
        var ce = $("<pre />").html(html);
        if ($.browser.webkit) {
            ce.find("div").replaceWith(function() { return "\n" + this.innerHTML; });
        }
        if ($.browser.msie) {
            ce.find("p").replaceWith(function() { return this.innerHTML + "<br>"; });
        }
        if ($.browser.mozilla || $.browser.opera || $.browser.msie) {
            ce.find("br").replaceWith("\n");
        }
        return ce.text();
    });

    /**
     * App.Helper.Format.Nl2Br
     *
     * @param str, convert /n to <br />
     * @return string
     */
    App.provide('Helper.Format.Nl2Br', function (str) {
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2');
    });

    /**
     * App.Helper.Format.Slugify
     *
     * @param text, text to slugify
     * @return string slugified text
     */
    App.provide('Helper.Format.Slugify', function (text) {
        if (text === undefined || typeof text !== 'string') throw 'Slugify need a text as parameter slugify(text).'

        text = text.toLowerCase();

        // Source: http://milesj.me/snippets/javascript/slugify
        text = text.replace(/[^-a-zA-Z0-9&\s]+/ig, '');
        text = text.replace(/-/gi, '_');
        text = text.replace(/\s/gi, '-');

        return text;
    });

    /**
     * App.Helper.Format.Truncate
     *
     * @param text, text to truncate
     * @param length, default: 30
     * @param endChars, default: '...'
     * @return string truncate at first line break and after given length
     */
    App.provide('Helper.Format.Truncate', function (text, length, endChars) {
        length = length || 30;
        endChars = endChars || '...';

        var result = /(.*)\n?/.exec(text);

        if (result && result[1]) {
            return (result[1].length > length) ? result[1].substr(0, 30) + endChars : result[1];
        } else {
            return text;
        }
    });

})(Dime, jQuery, moment);

