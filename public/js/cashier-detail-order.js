$(function() {
    let total_price = 0;
    let total_price_after_voucher = 0;
    let total_price_after_other = 0;
    $('.orderList').find('tbody > tr').each(function() {
        let price = $(this).find('.price').text().replace(',', '');
        total_price += parseInt(price);
    })
    $('#draf_money').text(total_price);
    if (currentPrice != 0) {
        $('#draf_money').text(currentPrice);
    }
    $(document).on('keyup', '#other-money', function() {
        let other = $(this).val();
        total_price_after_other = total_price + parseInt(other);
        if (total_price_after_voucher !== 0) {
            total_price_after_other = total_price_after_voucher + parseInt(other);
        }
        $('#draf_money').text(total_price_after_other)
        if ((other === '' || other === 0) && $('#voucher').val() === 0) {
            $('#draf_money').text(total_price)
        }
        if ((other === '' || other === 0)  && $('#voucher').val() !== 0) {
            $('#draf_money').text(total_price - total_price * parseInt($('#voucher').val()) / 100)
        }
        let total_price_s = $('#draf_money').text();
        total_price_s = new Intl.NumberFormat('vi-Vi', { style: 'currency', currency: 'VND' }).format(total_price_s);
        $('#draf_money').text(total_price_s)
    })
    $(document).on('change', '#voucher', function() {
        let voucher = $(this).val();
        let total_price_after_other = $('#other-money').val();
        if (voucher !== 0) {
            total_price_after_voucher = total_price - total_price * parseInt(voucher) / 100;
            if (total_price_after_other !== '') {
                total_price_after_other = parseInt(total_price_after_other);
                total_price_after_voucher = total_price + total_price_after_other - (total_price_after_other + total_price) * parseInt(voucher) / 100;
            }
            $('#draf_money').text(total_price_after_voucher)
        }
        if (voucher === 0) {
            if (total_price_after_other === 0) {
                $('#draf_money').text(total_price)
            }
            if (total_price_after_other !== 0) {
                $('#draf_money').text(total_price + parseInt($('#other-money').val()))
            }
        }
        let total_price_s = $('#draf_money').text();
        total_price_s = new Intl.NumberFormat('vi-Vi', { style: 'currency', currency: 'VND' }).format(total_price_s);
        $('#draf_money').text(total_price_s)
    })
    let total_price_s = $('#draf_money').text();
    total_price_s = new Intl.NumberFormat('vi-Vi', { style: 'currency', currency: 'VND' }).format(total_price_s);
    $('#draf_money').text(total_price_s)
})
