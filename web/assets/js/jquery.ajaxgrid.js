;(function($){jQuery.fn.ajaxgrid = function(options){

        options = $.extend({
            ajax_url: '',
            array_sorted_fields: '',
            array_field_filters: '',
            url_notes_edit: '',
            url_notes_delete: '',
            result_array: '',
            filter_active: true,
            filters: {},
            all_notes: 0,
            notes_per_page: 10,
            page: 1,
            sort_direction: "ASC",
            sort_field: ''
        }, options);

        var make = function(){
            root = this;
            options.sort_field = options.array_sorted_fields[0];
            options.result_array = sendAjax(options);
            drawFilters(options);
            drawTable(options);
        };

        return this.each(make);
    };

    function drawFilters(options)
    {
        if (options.filter_active) {
            $panel_filters = $('<div class="filters_panel"></div>');

            $.each(options.array_field_filters[0], function(index, value ) {
                $input_type = options.array_field_filters[1][index];
                if ($input_type === 'text') {
                    $input_name = options.array_field_filters[0][index];
                    $input_placeholder = options.array_field_filters[0][index];
                    $field = $('<input class="form-control filter" type="' + $input_type + '" name="' + $input_name + '" placeholder="' + $input_placeholder + '">');
                    $panel_filters.append($field);
                } else {
                    if ($input_type === 'date') {
                        $date_contain = $('<div class="date_contain"></div>');
                        $date_contain_with = $('<div class="date"></div>');
                        $date_contain_until = $('<div class="date"></div>');

                        $input_name = options.array_field_filters[0][index];
                        $date_field_with = $('<input class="form-control date date_with filter" type="' + $input_type + '" name="' + $input_name + ' with">');
                        $date_field_until = $('<input class="form-control date date_until filter" type="' + $input_type + '" name="' + $input_name + ' until">');

                        $date_contain_with.append($date_field_with);
                        $date_contain_until.append($date_field_until);
                        $date_contain.append($date_contain_with);
                        $date_contain.append($date_contain_until);

                        $panel_filters.append($date_contain);
                    }
                }
            });

            $btn_filter = $('<button class="btn btn-default add_filter">Add Filters</button>');
            $btn_filter.on('click', function (e) {
                options.filters = {};
                $('.filter').each(function (i, elem) {
                    if ($(elem).val() !== '') {
                        options.filters[$(elem).attr('name')] = $(elem).val();
                    }
                });
                drawContainTable(options);
            });
            $panel_filters.append($btn_filter);
            $(root).append($panel_filters);
        }
    }

    function drawTable(options) {
        $table = $('<table class="table" id="ajax_table" border="1" width="100%" cellpadding="5"></table>');
        $table.append(htmlAddHeaders(options.result_array[0], options));
        $table.css("background-color","white");
        drawContainTable(options);
        $(root).append($table);
        drawPaginationTable(options);
    }

    function drawContainTable(options) {
        $(root).children('table').find('.contain_row').remove();
        $content = $('<tbody></tbody>');
        $table.append($content);
        options.result_array = sendAjax(options);
        $.each(options.result_array, function( index, value ) {
            $content.append(htmlAddRow(options.result_array[index], index+1, options));
        });
    }

    function drawPaginationTable(options) {
        $all_notes = options.all_notes;
        $notes_per_page = options.notes_per_page;
        if ($all_notes <= $notes_per_page) {
            $all_pages = 1;
        }
        else {
            $all_pages = parseInt($all_notes/$notes_per_page);
            if ($all_notes%$notes_per_page > 0) {
                $all_pages++;
            }
        }
        $panel_pagination = $('<div class="panel_pagination"></div>');
        $btn_next = $('<button class="btn btn-default" id="btn_next"><span class="glyphicon glyphicon-chevron-right"></span></button>');
        $btn_prev = $('<button class="btn btn-default" id="btn_prev"><span class="glyphicon glyphicon-chevron-left"></span></button>');
        $view_page = $('<span class="view_page"> - <b>' + $all_pages + '</b></span>');
        $text_find = $('<input type="text" class="current_page" value="' + options.page + '"></input>');
        $btn_next.on('click', function (e) {
            if (options.page < $all_pages) {
                options.page++;
                $('.current_page').val(options.page);
                drawContainTable(options);
            }
        });
        $btn_prev.on('click', function (e) {
            if (options.page > 1) {
                options.page--;
                $('.current_page').val(options.page);
                drawContainTable(options);
            }
        });
        $text_find.on('keyup', function (e) {
            input = $(this);
            if ((input.val() >= 1) && (input.val() <= $all_pages)) {
                options.page = input.val();
                $('.current_page').val(options.page);
                drawContainTable(options);
            }
        });
        $panel_pagination.append($btn_prev);
        $panel_pagination.append($text_find);
        $panel_pagination.append($view_page);
        $panel_pagination.append($btn_next);
        $(root).append($panel_pagination);
    }

    function htmlAddHeaders($array_headers, options){
        $head = $('<thead class="thead-light"></thead>');
        $row = $('<tr class="header_row"></tr>');
        $head.append($row);
        $.each($array_headers, function( index, value ) {

            $contain = $('<th scope="col"></th>');
            $icon_sort = '<span></span>';
            $header = $('<a class="sort_header" href="#">' + index + $icon_sort +'</a>');

            if ($.inArray( index, options.array_sorted_fields) !== -1) {
                $header.on('click', function (e) {

                    if (options.sort_field === index) {
                        if (options.sort_direction === 'ASC')
                            options.sort_direction = "DESC";
                        else
                            options.sort_direction = "ASC";
                    } else {
                        options.sort_field = index;
                        options.sort_direction = 'ASC';
                    }
                    drawContainTable(options);
                    setIconSort($(this).find('span'));
                });
            }
            $contain.append($header);
            $row.append($contain);
        });
        return $head;
    }

    function setIconSort($elem) {
        if ($($elem).hasClass('glyphicon')) {
            if ($($elem).hasClass('glyphicon-sort-by-attributes-alt')) {
                $($elem).removeClass();
                $($elem).addClass('glyphicon glyphicon-sort-by-attributes');
            } else {
                $($elem).removeClass();
                $($elem).addClass('glyphicon glyphicon-sort-by-attributes-alt');
            }
        } else {
            $($elem).addClass('glyphicon glyphicon-sort-by-attributes');
        }
        checkIconSort($elem);
    }

    function sendAjax(options) {
        var $array = [];
        $.ajax({
            url: options.ajax_url,
            type: "POST",
            dataType: "json",
            data: {
                "notes_per_page": options.notes_per_page,
                "page": options.page,
                "sort_direction": options.sort_direction,
                "sort_field": options.sort_field,
                "filters": options.filters
            },
            async: false
        })
            .done(function (data) {
                console.log(data);
                $.each(data.result, function(key, val){
                    $array[key] = val;
                });
                options.all_notes = data.all_notes[0]['all_notes'];
            });
        return $array;
    }

    function checkIconSort($elem) {
        $icons = $('.header_row').find('.glyphicon');
        if ($icons.length > 1) {
            $icons.removeClass();
            $($elem).addClass('glyphicon glyphicon-sort-by-attributes');
        }
    }

    function htmlAddRow($array_row, index, options) {
        $row = $('<tr class="contain_row"></tr>');
        $.each($array_row, function( index, value ) {
            $contain = $('<td>' + value + '</td>');
            $row.append($contain);
        });
        if ((options.url_notes_edit.length !== 0) || (options.url_notes_delete.length !== 0)) {
            $contain = $('<td></td>');
            if (options.url_notes_edit.length !== 0) {
                button_edit = $(
                    '<a class="btn btn-default" href="' +
                    options.url_notes_edit[0] +
                    '/' +
                    $array_row[options.url_notes_edit[1]] + '">' +
                    '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>' +
                    '</a>'
                );
                $contain.append(button_edit);
            }
            if (options.url_notes_delete.length !== 0) {
                button_delete = $(
                    '<a class="btn btn-default" href="' +
                    options.url_notes_delete[0] +
                    '/' +
                    $array_row[options.url_notes_delete[1]] + '">' +
                    '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>' +
                    '</a>'
                );
                $contain.append(button_delete);
            }

            $row.append($contain);
        }
        return $row;
    }
})(jQuery);