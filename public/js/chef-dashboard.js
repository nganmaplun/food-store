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
                showMessage(2000, response.message);
                setTimeout(function () {
                    location.reload();
                }, 2000)
            },
        });
    });

    $(document).on("click", ".cancel-food", function () {
        let index = $(this).attr("index");
        $.ajax({
            url: urlCancelFood,
            type: "POST",
            data: {
                foodOrderId: index,
            },
            success: function (response) {
                showMessage(2000, response.message);
                setTimeout(function () {
                    location.href = domain + '/chef/setting-foods';
                }, 1000)
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
