$(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    setTimeout(function () {
        $('.alert-success').prop('hidden', true);
    }, 2000);
})

function showMessage(time, msg)
{
    $(document).Toasts('create', {
        class: 'bg-info',
        title: 'Thành công',
        autohide: true,
        delay: time,
        body: msg
    })
}
