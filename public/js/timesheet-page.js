$(function () {
    $(document).on("click", ".approved, .disapproved", function () {
        let index = $(this).parent().parent().attr("index");
        let type = $(this).hasClass("approved") ? "approved" : "disapproved";
        let data = {
            index: index,
            type: type,
        };
        $.ajax({
            url: urlApprove,
            type: "POST",
            data: data,
            success: function (response) {
                if (!response.status) {
                    alert("Có lỗi xảy ra, vui lòng thử lại!");
                    return;
                }
                alert("Duyệt timesheet cho nhân viên thành công!");
                location.reload();
            },
        });
    });
});
