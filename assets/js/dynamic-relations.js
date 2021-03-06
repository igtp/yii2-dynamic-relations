var dynamicRelations = function () {
    function scriptLoaded(url) {
        var scripts = document.getElementsByTagName('script');
        for (var i = scripts.length; i--;) {
            if (scripts[i].src == url) return true;
        }
        return false;
    }

    function cssLoaded(url) {
        var link = document.getElementsByTagName('link');
        for (var i = link.length; i--;) {
            if (link[i].href == url) return true;
        }
        return false;
    }

    //$(window).bind("load", function () {
        //hide and show blocks with class js_hide
        $('.js_hide').each(function () {
            var dataTitle = $(this).attr('data-title');
            var showButton = '<div class="js_show-next-hidden-block" >' + dataTitle + '<div class="show-subtitle"> (Показать/скрыть <i class="glyphicon glyphicon-arrow-down"></i> )  </div></div>';
            $(this).before(showButton);
            $(this).hide();


        });
        $('.js_show-next-hidden-block').each(function () {
            $(this).on('click', function () {
                $(this).next().slideToggle(750);
            })
        });
        $('.js_show-all-hidden-blocks').each(function () {
            $(this).on('click', function () {
                $(this).next().sublincs('js_hide').slideToggle(750);
            })
        });

        $('select').select2()
            .on("change", function (e) {
                // mostly used event, fired to the original element when the value changes
                console.log("change val=" + e.val);
                console.log(e);
            });
    //});

    //jQuery(document).ready(function () {

        var removeFn = function (sel) {
            jQuery('.remove-dynamic-relation').on('click', function (event) {
                event.preventDefault();
                var me = this;
                var myLi = jQuery(me).closest('li');
                removeRoute = jQuery(this).parent().find("[data-dynamic-relation-remove-route]").attr("data-dynamic-relation-remove-route");
                if (removeRoute) {
                    jQuery.post(removeRoute, function (result) {
                        myLi.remove()
                    });
                }
                else {
                    myLi.remove();
                }
            });
        };


        var addFn = function() {
            jQuery('.add-dynamic-relation').on('click', function (event) {
                event.preventDefault();
                var me = this;
                view = jQuery(me).closest('[data-related-view]').attr('data-related-view') + "&t=" + ( new Date().getTime() );
                jQuery.get(view, function (result) {
                    $result = jQuery(result);
                    li = jQuery(me).closest('li').clone().empty();
                    ul = jQuery(me).closest('ul');
                    var relDiv = $('<div />', {
                        "class": 'dynamic-relation-container',
                        });
                    relDiv.append($result.filter("#root"));
                    ul.append(li);
                    li.append(relDiv);
                    $result.filter('script').each(function (k, scriptNode) {
                        if (!scriptNode.src || !scriptLoaded(scriptNode.src)) {
                            jQuery("body").append(scriptNode);
                        }
                    });
                    $result.filter('link').each(function (k, linkNode) {
                        if (!cssLoaded(linkNode.href)) {
                            jQuery("head").append(linkNode);
                        }
                    });
                    removeFn(li.find('.remove-dynamic-relation'));
                });
            });
        };
        removeFn('.remove-dynamic-relation');
        addFn();
    //})
};
dynamicRelations();

