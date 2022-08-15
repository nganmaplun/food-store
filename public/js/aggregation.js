$(function () {
    $('#aggOrders').DataTable({
        paging: true,
        lengthChange: true,
        searching: false,
        ordering: true,
        info: false,
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            orderable: false,
            targets: "no-sort"
        }],
        lengthMenu: [5, 10, 20, 50, 100],
    });
    $('#from-sm, #to-sm').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('#from-sm, #to-sm').on('change', function() {
        $('#aggOrders').DataTable().destroy();
        var from = $('#from-sm').val()
        var to = $('#to-sm').val()
        $.ajax({
            url: ocustomUrl,
            type: 'POST',
            data: {
                date_from : from,
                date_to : to,
            }, success:function (response) {
                let html = '';
                $('#aggOrders').find('tbody').html(html);
                let key = 0;
                let vie_guest = 0;
                let jap_guest = 0;
                let oth_guest = 0;
                let total_food = 0;
                let total_price = 0;
                let date_s = '';
                for (let i = 0; i < response.length; i++) {
                    key += 1;
                    if (response[i].hasOwnProperty('vietnamese_guest')) {
                        vie_guest = response[i].vietnamese_guest;
                    }
                    if (response[i].hasOwnProperty('japanese_guest')) {
                        jap_guest = response[i].japanese_guest;
                    }
                    if (response[i].hasOwnProperty('other_guest')) {
                        oth_guest = response[i].other_guest;
                    }
                    if (response[i].hasOwnProperty('total_food')) {
                        total_food = response[i].total_food;
                    }
                    if (response[i].hasOwnProperty('total_price')) {
                        total_price = response[i].total_price;
                    }
                    if (response[i].hasOwnProperty('order_date')) {
                        date_s = response[i].order_date;
                    }
                    html += '<tr>';
                    html += '<td>' + key + '</td>';
                    html += '<td>' + vie_guest + '</td>';
                    html += '<td>' + jap_guest + '</td>';
                    html += '<td>' + oth_guest + '</td>';
                    html += '<td>' + total_food + '</td>';
                    html += '<td>' + total_price + '</td>';
                    html += '<td>' + date_s + '</td>';
                    html += '</tr>';
                }
                $('#aggOrders').find('tbody').html(html);
                $('#aggOrders').DataTable({
                    paging: true,
                    lengthChange: true,
                    searching: false,
                    ordering: true,
                    info: false,
                    autoWidth: false,
                    responsive: true,
                    columnDefs: [{
                        orderable: false,
                        targets: "no-sort"
                    }],
                    lengthMenu: [5, 10, 20, 50, 100],
                });
            }
        })
    });

    $(document).on('change', '#agg-type', function() {
        let type = $(this).val();
        let arType = ['day', 'month', 'year', ''];
        if (arType.includes(type)) {
            location.href = optionUrl.replace('cus-type', type);
            return;
        }
        $('#custom-date').prop('hidden', false);
        window.history.pushState({}, null, baseUrl);
    })
});
