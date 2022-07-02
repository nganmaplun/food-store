$(function () {
    $(document).on('click', '#send-chef', function () {
        let data = {
            orderId: orderId,
            tableId: tableId,
            messageType: 'send-chef'
        };
        sendRequest(data);
    });
    $(document).on('click', '#send-cashier', function () {
        let data = {
            orderId: orderId,
            tableId: tableId,
            messageType: 'send-cashier'
        };
        sendRequest(data);
    });
})
function sendRequest(data)
{
    return $.ajax({
        url: sendMessage,
        type: 'POST',
        data: data,
        success: function (response) {
            alert(response.message);
        }
    })
}
