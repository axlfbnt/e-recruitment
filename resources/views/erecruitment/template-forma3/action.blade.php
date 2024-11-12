<div class="btn-group" role="group">
    <button type="button"
        class="btn btn-sm btn-toggle-status {{ $data->status == 'Active' ? 'btn-success' : 'btn-success' }}"
        data-id="{{ $data->id_form_a3 }}" data-status="{{ $data->status }}">
        <i class="{{ $data->status == 'Active' ? 'ri-toggle-fill' : 'ri-toggle-line' }}"></i>
    </button>

    <button type="button" data-id="{{ $data->id_form_a3 }}" data-bs-toggle="modal"
        data-bs-target="#edittemplateformA3-modal" title="Edit" class="btn btn-warning btn-sm button-edit"
        {{ $data->status == 'Active' ? 'disabled' : '' }}>
        <i class="ri-pencil-line"></i>
    </button>

    <button type="button" data-id="{{ $data->id_form_a3 }}" data-bs-toggle="modal"
        data-bs-target="#deletetemplateforma3-modal" title="Delete" class="btn btn-danger btn-sm button-delete"
        {{ $data->status == 'Active' ? 'disabled' : '' }}>
        <i class="ri-delete-bin-line"></i>
    </button>
</div>
