$(function () {
    $('#listFoods').DataTable({
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: false,
        autoWidth: false,
        responsive: true,
        columns : [
            { width : '50px' },
            { width : '50px' },
            { width : '50px' },
        ],
        columnDefs: [{
            orderable: false,
            targets: "no-sort"
        }],
        lengthMenu: [5, 10, 20, 50, 100],
    });

    $(document).on('click', '.setting', function() {
        let setNumber, fid, index;
        let thisTr = $(this).closest('tr');
        let otherTr = $(this).closest('tr').prev();
        if (otherTr.hasClass('dt-hasChild')) {
            index = otherTr.attr('index');
            fid = otherTr.attr('fid');
            setNumber = otherTr.find('.food-count').val();
        } else {
            index = thisTr.attr('index');
            fid = thisTr.attr('fid');
            setNumber = thisTr.find('.food-count').val();
        }
        let today = $('#today').text();
        if (setNumber < 0 || setNumber === '') {
            alert('Hãy nhập số suất sẽ bán trong ngày!');
            thisTr.find('.food-count').val('');
            return;
        }
        $.ajax({
            url: setFoodUrl,
            type: 'POST',
            data: {
                index: index,
                fid: fid,
                setNumber: setNumber,
                today: today
            },
            success: function (response) {
                if (!response.status) {
                    alert('Lỗi hệ thống, vui lòng đặt lại!');
                    return;
                }
                alert('Đặt số suất sẽ bán trong ngày thành công!');
                location.reload();
            }
        })
    })
});

function openModal(that) {
    $('#modal-setting-food').modal('show');
    $('.food-name').val('');
    $('.food-num').val('');
    $('.food-name').autocomplete({
        source: listAllName
    });
    $('.set-food').on('click', function () {
        let foodName = $('.food-name').val();
        let setNumber = $('.food-num').val();
        let today = $('#today').text();
        if (setNumber < 0 || setNumber === '') {
            alert('Hãy nhập số suất sẽ bán trong ngày!');
            $('.food-num').val('');
            return;
        }
        $.ajax({
            url: setFoodUrl,
            type: 'POST',
            data: {
                fid: foodName,
                setNumber: setNumber,
                today: today
            },
            success: function (response) {
                if (!response.status) {
                    alert('Lỗi hệ thống, vui lòng đặt lại!');
                    return;
                }
                alert('Đặt số suất sẽ bán trong ngày thành công!');
                location.reload();
            }
        })
    })
}
