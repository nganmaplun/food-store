$(function (){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
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
