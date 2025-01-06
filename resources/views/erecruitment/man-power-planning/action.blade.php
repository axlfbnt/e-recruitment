<div class="btn-group" role="group">
    <button type="button" data-id="{{ $data->id_mpp }}" title="Detail" class="btn btn-success btn-sm button-detail">
        <i class="ri-eye-line"></i>
    </button>
    <button type="button" data-id="{{ $data->id_mpp }}" data-bs-toggle="modal" data-bs-target="#editmpp-modal" title="Edit" class="btn btn-warning btn-sm button-edit">
        <i class="ri-edit-2-line"></i>
    </button>
    <button type="button" data-id="{{ $data->id_mpp }}" data-bs-toggle="modal" data-bs-target="#deletempp-modal" title="Remove" class="btn btn-danger btn-sm button-delete">
        <i class="ri-delete-bin-5-line"></i>
    </button>
</div>