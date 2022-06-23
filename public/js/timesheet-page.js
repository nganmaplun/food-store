$(function () {
    $('.approved').on('click', function() {
        let index = $(this).parent().parent().attr('index');
        $.ajax({
            url: urlApprove,
            type: 'POST',
            data : { index: index},
            success:function (response) {
                if (!response.status) {
                    alert('Nhân viên chưa checkout!');
                    return;
                }
                alert('Duyệt timesheet cho nhân viên thành công!');
            }
        })
    });
});
