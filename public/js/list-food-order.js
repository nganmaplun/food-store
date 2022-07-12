$(function () {
    $(document).on('click', '.minus-less', function () {
        let orderNum = parseInt($(this).parent().find('.order-value').text());
        let minusNum = orderNum - 1;
        if (minusNum < 1) {
            $(this).parent().find('.order-value').text(1);
            return;
        }
        $(this).parent().find('.order-value').text(minusNum)
    })

    $(document).on('click', '.add-more', function () {
        let orderNum = parseInt($(this).parent().find('.order-value').text());
        let addNum = orderNum + 1;
        $(this).parent().find('.order-value').text(addNum)
    })

    $(document).on('click', '.add-note', function() {
        let note = $('#modal-food-note').find('.food-note').val();
        let index = $(this).attr('index');
        $('.search-for-index-' + index).find('.hidden-note').text(note);
        $('#modal-food-note').modal('hide');
    })

    $(document).on('click', '.btn-order', function () {
        let foodId = $(this).attr('index');
        let orderNum = parseInt($(this).parent().find('.order-value').text());
        let note = $(this).parent().find('.hidden-note').text()
        $.ajax({
            url: addToOrderUrl,
            type: "POST",
            data: {
                orderId: orderId,
                foodId: foodId,
                orderNum: orderNum,
                note: note
            },
            success:function (response) {
                showMessage(2000, response.message);
            }
        });
    })
});

function openModal(that)
{
    $('#modal-food-note').modal('show');
    let index = $(that).attr('index');
    let oldNote = $('.search-for-index-' + index).find('.hidden-note').text();
    $('#modal-food-note').find('.food-note').val(oldNote);
    $('#modal-food-note').find('.add-note').attr('index', index);
}
