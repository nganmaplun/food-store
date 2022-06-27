<div class="modal fade" id="modal-order">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tạo order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <label>Loại khách hàng</label>
                        <select class="form-control guest-type">
                            <option value="V">Khách Việt</option>
                            <option value="J">Khách Nhật</option>
                            <option value="E">Khách khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="guest-num">Số lượng khách</label>
                        <input type="text" class="form-control guest-num">
                    </div>
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea class="form-control other-note" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary add-order">Tạo</button>
            </div>
        </div>
    </div>
</div>
