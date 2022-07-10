$(function () {
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
})
function sendRequest(data, url = '')
{
    return $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            alert(response.message);
            location.reload();
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
    if (arrayFoods.length === 0) {
        if (type === 'chef') {
            alert('Hãy chọn món mới trước khi gửi');
        } else {
            alert('Các món chưa lên hết, hủy món nếu vẫn muốn thanh toán');
        }
        return 'false';
    }
}
