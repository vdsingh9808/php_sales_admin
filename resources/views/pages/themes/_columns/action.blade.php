<div class="d-flex">
    <div>
        <button type="button"
                class="btn btn-outline-secondary border-none rounded-pill d-flex align-items-center ms-auto"
                onclick="window.location.href='{{ route('themes.edit', $model->id) }}'">
            <i class='bx bx-show-alt'></i>
            &nbsp;&nbsp;View
        </button>
    </div>
    <div class="ps-2">
        <button type="button"
                class="btn btn-outline-secondary border-none rounded-pill d-flex align-items-center ms-auto p-2"
                data-bs-toggle="modal" data-bs-target="#deleteModal_{{$model->id}}">
            <i class='bx bx-trash'></i>
            &nbsp;&nbsp;Delete
        </button>
    </div>
</div>

<div class="modal fade" id="deleteModal_{{$model->id}}" tabindex="-1" aria-labelledby="deleteModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                Are you sure you want to delete this theme?
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('themes.destroy', $model->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-primary" type="submit" class="btn btn-danger">Delete</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
