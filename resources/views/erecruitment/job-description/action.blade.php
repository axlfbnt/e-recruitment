<div class="btn-group" role="group">
    <button type="button" data-id="{{ $data->id_jobdesc }}" data-bs-toggle="modal" data-bs-target="#editjobdesc-modal" title="Edit" class="btn btn-warning btn-sm button-edit">
        <i class="ri-edit-2-line"></i>
    </button>
    <button type="button" data-id="{{ $data->id_jobdesc }}" data-bs-toggle="modal" data-bs-target="#deletejobdesc-modal" title="Remove" class="btn btn-danger btn-sm button-delete">
        <i class="ri-delete-bin-5-line"></i>
    </button>
</div>