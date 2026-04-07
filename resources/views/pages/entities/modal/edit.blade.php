<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="{{ asset('assets/img/icons/unicons/modal.png') }}" alt="Icon">
                    <h5 class="modal-title mt-4" id="exampleModalLabel">Edit Entity</h5>
                    <p>Modify and update entities.</p>

                </div>
                <form id="entity-update-form" method="post" action="" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Entity</label>
                        <input type="text" class="form-control" id="name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Theme</label>
                        <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example"
                                name="theme_id">
                            @foreach($themes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">API key</label>
                        <input type="text" class="form-control" id="api-key" name="api_key">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Reference Key</label>
                        <input type="text" class="form-control" id="reference-key" name="reference_key">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" id="submit-form" class="btn btn-primary w-50">Save</button>
                        <button type="button" class="btn btn-outline-secondary w-50" data-bs-dismiss="modal">Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
