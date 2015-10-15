/**
 * Created by Shen.L on 2015/10/12.
 */


var initS2Loading = function () {
}, initS2Open = function () {
}, initS2Unselect = function () {
};
(function ($) {
    "use strict";
    initS2Loading = function (id, containerCss) {
        var $el = $('#' + id), $container = $(containerCss),
            $loading = $('.kv-plugin-loading.loading-' + id),
            $group = $('.group-' + id);
        if (!$container.length) {
            $el.show();
        }
        if ($group.length) {
            $group.removeClass('kv-input-group-hide').removeClass('.group-' + id);
        }
        $loading.remove();
    };
    initS2Open = function () {
        var $el = $(this), $drop = $(".select2-container--open"),
            cssClasses, i, $src = $el.parents("[class*='has-']");
        if ($src.length) {
            cssClasses = $src[0].className.split(/\s+/);
            for (i = 0; i < cssClasses.length; i++) {
                if (cssClasses[i].match("has-")) {
                    $drop.removeClass("has-success has-error has-warning").addClass(cssClasses[i]);
                }
            }
        }
        if ($el.data('unselecting')) {
            $el.removeData('unselecting');
            setTimeout(function () {
                $el.select2('close').trigger('krajeeselect2:cleared');
            }, 5);
        }
    };
    initS2Unselect = function () {
        $(this).data('unselecting', true);
    };
})(window.jQuery);