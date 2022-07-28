// only for waiter
var pusher = new Pusher(app_key, {
    cluster: 'ap1',
    encrypted: true
});

var channel = pusher.subscribe('NotifyWaiter');

channel.bind('waiter-channel', function(data) {
    let link = domain + '/waiter/' + data.tableId + '/' + data.orderId;
    if (data.createTable) {
        link = domain + '/waiter/waiter-dashboard';
    }
    if (data.paid) {
        link = domain + '/waiter/detail/' + data.orderId;
    }
    let bodyText = "<span>Hãy bấm vào <a style='color: red' href='" + link + "'>đây</a> để kiểm tra lại</span>";
    $(document).Toasts('create', {
        class: 'bg-info',
        title: data.createTable || data.paid ? 'Cập nhật thông tin bàn' : 'Có món đưa ra từ bếp',
        body: bodyText
    })
});
