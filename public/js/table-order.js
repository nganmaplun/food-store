$(function () {
    $('.eng, .jap').prop('hidden', true);
    $(document).on('change', '#lang-change', function() {
        if ($(this).val() == 'vie') {
            $('.eng, .jap').prop('hidden', true);
            $('.vie').prop('hidden', false);
        }
        if ($(this).val() == 'jap') {
            $('.eng, .vie').prop('hidden', true);
            $('.jap').prop('hidden', false);
        }
        if ($(this).val() == 'eng') {
            $('.vie, .jap').prop('hidden', true);
            $('.eng').prop('hidden', false);
        }
    });
    $('#list-order').DataTable({
        paging: true,
        lengthChange: true,
        searching: false,
        ordering: false,
        info: false,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: "no-sort"},
        ],
        lengthMenu: [10, 20, 50, 100],
    });

    $(document).on('click', '#send-chef', function () {
        let tblRows = $('#list-order > tbody > tr');
        let check = validateRows(tblRows, 'chef');
        if (check === 'false') return;
        let data = {
            orderId: orderId,
            tableId: tableId,
            messageType: 'send-chef'
        };
        sendRequest(data, sendMessage);
    });
    $(document).on('click', '#send-cashier', function () {
        let tblRows = $('#list-order > tbody > tr');
        let check = validateRows(tblRows, 'cash');
        if (check === 'false') return;
        let data = {
            orderId: orderId,
            tableId: tableId,
            messageType: 'send-cashier'
        };
        sendRequest(data, sendMessage);
    });
    $(document).on('click', '.btn-delete', function() {
        let index = $(this).attr('index');
        let data = {
            orderId: orderId,
            tableId: tableId,
            messageType: 'send-chef',
            tId: index
        }
        sendRequest(data, sendMessageDelete);
    })

    $(document).on('click', '.to-table', function () {
        let index = $(this).attr('index');
        let data = {
            index: index
        }
        sendRequest(data, urlFoodToTable);
    });
})
function sendRequest(data, url = '')
{
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            showMessage(2000, response.message);
            setTimeout(function () {
                location.reload();
            }, 2000)
        }
    })
}
function validateRows(tblRows, type)
{
    if (tblRows.length === 0) {
        alert('Hãy chọn món trước khi gửi');
        return 'false';
    }
    let arrayFoods = [];
    for (let i = 0;i < tblRows.length; i++) {
        if (!$(tblRows[i]).hasClass('bg-orange')) {
            arrayFoods.push(i);
        }
    }
    if (arrayFoods.length === 0 && type === 'chef') {
        alert('Hãy chọn món mới trước khi gửi');
        return 'false';
    }
    if (arrayFoods.length > 0 && type === 'cash') {
        alert('Các món chưa lên hết, hủy món nếu vẫn muốn thanh toán');
        return 'false';
    }
}

function openModal(that) {
    let order = JSON.parse(orderInfo.replace(/&quot;/g, '"'));
    console.log(order);
    $("#modal-order").modal("show");
    $("#modal-order").find(".guest-type").prop('disabled', true).val(order.customer_type);
    $("#modal-order").find(".guest-num").prop('disabled', true).val(order.number_of_customers);
    $("#modal-order").find(".other-note").val(order.description);
    $("#modal-order").find(".add-order").text('Sửa');
}
