$(function () {
    $(document).on("click", ".check-food", function () {
        let index = $(this).attr("index");
        let tblId = $(this).attr("rel");
        $.ajax({
            url: urlSendFoodToWaiter,
            type: "POST",
            data: {
                orderId: index,
                tableId: tblId,
                messageType: "send-waiter",
            },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
        });
    });
});
function openModal(that) {
    let key = $(that).attr("key");
    let foods = JSON.parse(listFoods.replace(/&quot;/g, '"'));
    $("#modal-food-recipe").modal("show");
    $("#modal-food-recipe").find("#recipe").text(foods[key]["food_recipe"]);
}
