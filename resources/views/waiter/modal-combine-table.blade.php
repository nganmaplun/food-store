<div class="modal fade" id="modal-combine-table">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Chọn bàn muốn gộp</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <select multiple="" class="custom-select" id="multi-table">
                                @foreach($listTables as $table)
                                    @if ($table[\App\Constants\BaseConstant::STATUS_FIELD] == 0)
                                        <option value="{{ $table[\App\Constants\BaseConstant::ID_FIELD] }}">{{ $table[\App\Constants\TableConstant::NAME_FIELD] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary combine-table">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
