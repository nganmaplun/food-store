$(function () {
    $('.projects').DataTable({
        paging: true,
        lengthChange: true,
        searching: false,
        ordering: false,
        info: false,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: "no-sort"},
        ],
        lengthMenu: [10, 20, 50, 100],
    });

    $(document).on("click", ".check-food", function () {
        let index = $(this).attr("index");
        let tblId = $(this).attr("rel");
        let fId = $(this).attr("food");
        $.ajax({
            url: urlSendFoodToWaiter,
            type: "POST",
            data: {
                orderId: index,
                tableId: tblId,
                foodId: fId,
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
