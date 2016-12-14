/**
 * @package        csSelectable - jQuery Plugin
 * @copyright      Copyright (C) 2015-2016 Cüneyt Şentürk. All rights reserved. (cuneytsenturk.com.tr)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

var fakePOS = false;

(function ($, window, document, undefined) {
    'use strict';

    var plugin = 'csSelectable';

    var defaults = {
        onSelect  : function () {
        },
        onUnSelect: function () {
        },
        onClear   : function () {
        }
    };

    var rb = function () {
        return document.getElementById('s-rectBox');
    };

    var self;

    var csSelectable = function (container, options) {
        this.container = $(container);
        this.options   = $.extend({}, defaults, options);
        this.selection = $('<div>').addClass(this.options.selectionClass);
        this.items     = $(this.options.items);

        this.init();
    };

    csSelectable.prototype.init = function () {
        if (!this.options.autoRefresh) {
            this.itemData = this._cacheItemData();
        }

        this.selecting = false;

        this._normalizeContainer();
        this._bindEvents();

        return true;
    };

    csSelectable.prototype._normalizeContainer = function () {
        this.container.css({
            '-webkit-touch-callout': 'none',
            '-webkit-user-select'  : 'none',
            '-khtml-user-select'   : 'none',
            '-moz-user-select'     : 'none',
            '-ms-user-select'      : 'none',
            'user-select'          : 'none'
        });
    };

    csSelectable.prototype._cacheItemData = function () {
        var itemData = [], itemsLength = this.items.length;

        for (var i = 0, item; item = $(this.items[i]), i < itemsLength; i++) {
            itemData.push({
                element  : item,
                selected : item.hasClass(this.options.selectedClass),
                selecting: false,
                position : item[0].getBoundingClientRect()
            });
        }

        return itemData;
    };

    csSelectable.prototype._collisionDetector = function () {
        var selector = this.selection[0].getBoundingClientRect(), dataLength = this.itemData.length;
        for (var i = dataLength - 1, item; item = this.itemData[i], i >= 0; i--) {
            var collided = !(selector.right < item.position.left ||

            selector.left > item.position.right ||
            selector.bottom < item.position.top ||
            selector.top > item.position.bottom);

            if (collided) {
                if (item.selected) {
                    item.element.removeClass(this.options.selectedClass);
                    item.selected = false;
                }

                if (!item.selected) {
                    item.element.addClass(this.options.selectedClass);
                    item.selected = true;

                    this.options.onSelect(item.element);
                }
            } else {
                if (this.selecting) {
                    item.element.removeClass(this.options.selectedClass);

                    this.options.onUnSelect(item.element);
                }
            }

        }
    };

    csSelectable.prototype._createSelection = function (e) {
        self = [e.pageX, e.pageY];

        this.selection[0].id = 's-rectBox';

        this.selection.css({
            'position': 'absolute',
            'top'     : e.pageY + 'px',
            'left'    : e.pageX + 'px',
            'width'   : '0',
            'height'  : '0',
            'z-index' : '99999',
            'overflow': 'hidden'
        }).appendTo('body');
    };

    csSelectable.prototype._drawSelection = function (e) {
        var g = rb();

        if (!self || g === null) {
            return;
        }

        var tmp, x1 = self[0], y1 = self[1], x2 = e.pageX, y2 = e.pageY;

        if (x1 > x2) {
            tmp = x2, x2 = x1, x1 = tmp;
        }

        if (y1 > y2) {
            tmp = y2, y2 = y1, y1 = tmp;
        }

        this.selection.css({
            'width' : (x2 - x1) + 'px',
            'height': (y2 - y1) + 'px',
            'top'   : y1 + 'px',
            'left'  : x1 + 'px'
        });
    };

    csSelectable.prototype.clear = function () {
        this.items.removeClass(this.options.selectedClass);

        this.options.onClear();
    };

    csSelectable.prototype._mouseDown = function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (this.options.disable) {
            return false;
        }

        if (event.which !== 1) {
            return false;
        }

        if (this.options.autoRefresh) {
            this.itemData = this._cacheItemData();
        }

        if (event.metaKey || event.ctrlKey) {
            this.selecting = false;
        } else {
            this.selecting = true;
        }

        this.selecting = false;

        this.pos = [event.pageX, event.pageY];

        this._createSelection(event);
    };

    csSelectable.prototype._mouseMove = function (event) {
        event.preventDefault();
        event.stopPropagation();

        var pos = this.pos;

        if (!pos) {
            return false;
        }

        this._drawSelection(event);
        this._collisionDetector();
    };

    csSelectable.prototype._mouseUp = function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (!this.pos) {
            return false;
        }

        this.selecting = false;
        this.selection.remove();

        if (event.pageX === this.pos[0] && event.pageY === this.pos[1] && !fakePOS) {
            this.clear();
        }

        if (fakePOS) {
            fakePOS = false;
        }
    };

    csSelectable.prototype._dblclick = function (event) {
        event.preventDefault();
        event.stopPropagation();

        this.clear();
    };

    csSelectable.prototype._bindEvents = function () {
        this.container.on('mousedown', $.proxy(this._mouseDown, this));
        this.container.on('mousemove', $.proxy(this._mouseMove, this));

        $(document).on('mouseup', $.proxy(this._mouseUp, this));
    };

    $.fn[plugin] = function (options) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function () {
            var item     = $(this),
                instance = item.data(plugin);

            if (!instance) {
                item.data(plugin, new csSelectable(this, options));
            } else {
                if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
                    instance[options].apply(instance, args);
                }
            }
        });
    };
})(jQuery, window, document);
