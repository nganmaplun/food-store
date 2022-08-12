// only for cashier
var pusher = new Pusher(app_key, {
    cluster: 'ap1',
    encrypted: true
});

var channel = pusher.subscribe('NotifyCashier');

channel.bind('cashier-channel', function(data) {
    let link = domain + '/cashier/dashboard';
    let bodyText = "<span>Hãy bấm vào <a style='color: red' href='" + link + "'>đây</a> để kiểm tra lại danh sách bàn</span>";
    $(document).Toasts('create', {
        class: 'bg-info',
        title: data.createTable ? 'Có thay đổi trạng thái bàn' : 'Có yêu cầu thanh toán',
        body: bodyText
    })
});
