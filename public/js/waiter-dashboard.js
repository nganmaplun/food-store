async function sendRequestChangeStatus(index) {
    return await $.ajax({
        url: changeStatusUrl,
        type: 'POST',
        data: {
            id: index
        },
        success:function(response) {
            return response
        }
    });
}
