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
async function sendRequestCreateOrder(index, guestType, guestNum, otherNote) {
    return await $.ajax({
        url: createOrder,
        type: 'POST',
        data: {
            id: index,
            guestType: guestType,
            guestNum: guestNum,
            otherNote: otherNote
        },
        success:function(response) {
            return response
        }
    })
}
async function createSubOrderTable(index, orderId, guestType, guestNum, otherNote) {
    return await $.ajax({
        url: createOrder,
        type: 'POST',
        data: {
            id: index,
            subIndex: orderId,
            guestType: guestType,
            guestNum: guestNum,
            otherNote: otherNote
        },
        success:function(response) {
            return response
        }
    })
}
