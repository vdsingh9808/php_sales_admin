$.extend( $.fn.dataTable.defaults, {
    searching: true,
    stateSave: true,
    dom: "<'table-responsive'<tr>>" +
        "<'card-footer'<'row'<'col col-md d-flex align-items-center justify-content-md-space-between 'l><'col-auto col-md-auto text-end'i><'col col-md-auto'p>>>",
    language: {
        info: "_START_-_END_ of _TOTAL_",
        infoFiltered: " / _MAX_",
        infoEmpty: "_START_-_END_ of _TOTAL_",
        lengthMenu: "Rows per page: _MENU_",
        processing: "<i class='fa fa-refresh fa-spin'></i>",
        paginate: {
            first: '<i class="mdi mdi-chevron-double-left"></i>',
            last: '<i class="mdi mdi-chevron-double-right"></i>',
            next: '<i class="mdi mdi-chevron-right"></i>',
            previous: '<i class="mdi mdi-chevron-left"></i>'
        },
    }
});

$.fn.dataTable.render.checkbox = function (valueProp, nameProp) {
    let valueFn = valueProp ? DataTable.util.get(valueProp) : null;
    let nameFn = nameProp ? DataTable.util.get(nameProp) : null;

    return function (data, type, row, meta) {
        if (type === 'display') {
            return '<input type="checkbox" class="form-check-input dt-checkboxes" autocomplete="off" name="'+(nameFn ? nameFn(row) : null)+'" value="'+(valueFn ? valueFn(row) : null)+'" >'
        }

        return data;
    }
}

$.fn.dataTable.render.editable = function (valueProp, nameProp) {
    let valueFn = valueProp ? DataTable.util.get(valueProp) : null;
    let nameFn = nameProp ? DataTable.util.get(nameProp) : null;

    return function (data, type, row, meta) {
        if (type === 'display') {
            // return '<input type="text" class="" autocomplete="off" name="'+(nameFn ? nameFn(row) : null)+'" value="'+(valueFn ? valueFn(row) : null)+'" />'
            // return '<div contenteditable="true" data-name="'+(nameFn ? nameFn(row) : null)+'" >'+(valueFn ? valueFn(row) : null)+'</div>'
            return '<input type="text" class="form-control form-control-sm my-n2 px-2" style="min-width: 50px" data-name="'+(nameFn ? nameFn(row) : null)+'" value="'+(valueFn ? valueFn(row) : null)+'" />'
        }

        return data;
    }
}

$(function() {
    'use strict';

    // Custom search input with auto-populate saved state
    $(document).on('keyup', '[data-dt-toggle="search"]', function () {
        let target = $(this).data('dt-target');
        let table = $(target).DataTable();
        table.search(this.value).draw();
    });

    $(document).on( 'init.dt', function ( e, settings ) {
        let api = new $.fn.dataTable.Api( settings );
        let state = api.state.loaded();
        let $searchEl = $('[data-dt-target="#'+e.target.id+'"][data-dt-toggle="search"]')

        if (!state || !state.search || !state.search.search) {
            return;
        }

        $searchEl.val(state.search.search);
    });

    $(document).on('change', '[data-dt-toggle="filter"][data-dt-target][data-dt-filter="change"]',function () {
        let target = $(this).data('dt-target');
        let table = $(target).DataTable();
        table.draw();
    });

    // Draw on click
    $(document).on('click', '[data-dt-toggle="draw"][data-dt-event="click"], [data-dt-toggle="draw"]:not([data-dt-event])', function () {
        let target = $(this).data('dt-target');
        let table = $(target).DataTable();
        table.draw();
    });

    $(document).on('change', '[data-dt-toggle="draw"][data-dt-event="change"]', function () {
        let target = $(this).data('dt-target');
        let table = $(target).DataTable();
        table.draw();
    });

    $(document).on('change', '[data-dt-target][data-dt-draw="change"]',function () {
        let target = $(this).data('dt-target');
        let table = $(target).DataTable();
        table.draw();
    });

    $(document).on('preXhr.dt', '.dataTable', function (e, settings, data) {
        let tableId = this.id;

        let elements = $('[data-dt-toggle="ajax"][data-dt-target="#'+tableId+'"]');
        elements.each(function () {
            let key = this.name ? this.name : this.id;
            data[key] = this.value;
        });

        let filterElements = $('[data-dt-toggle="filter"][data-dt-target="#'+tableId+'"]');
        data.filters = {};
        filterElements.each(function () {
            let key = this.name ? this.name : this.id;
            let value = this.value;

            if ($(this).is(":checkbox") && !$(this).prop("checked")) {
                value = null
            }

            data.filters[key] = value;
        });
    });

    $(document).on('select.dt deselect.dt', '.dataTable', function () {
        let table = $(this).DataTable();
        let tableId = $(this).attr('id');
        let rowsSelectedCount = table.rows( { selected: true } ).count();

        let $selectNoneEl = $('[data-dt-target="#'+tableId+'"][data-dt-toggle="selectNone"]');
        let selectNoneClassName = $selectNoneEl.attr('data-dt-toggleClass');

        let $selectAnyEl = $('[data-dt-target="#'+tableId+'"][data-dt-toggle="selectAny"]');
        let selectAnyClassName = $selectAnyEl.attr('data-dt-toggleClass');

        if (rowsSelectedCount === 0) {
            $selectNoneEl.addClass(selectNoneClassName);
            $selectAnyEl.removeClass(selectAnyClassName);
        } else {
            $selectNoneEl.removeClass(selectNoneClassName);
            $selectAnyEl.addClass(selectAnyClassName);
        }
    });


});
