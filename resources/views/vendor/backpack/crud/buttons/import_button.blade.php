
<!-- Button trigger modal -->
  <button type="button" class="btn btn-xs btn-default" data-toggle="modal" data-target="#exampleModal"><em class="la la-cloud-upload-alt"></em>
    Import
    <input type="hidden" name="model-name" value="{{ ucFirst($crud->entity_name) }}">
    @foreach ($crud->model->importable as $importableField)
      <input type="hidden" name="fields" value="{{ $importableField }}">
    @endforeach
  </button>
  
  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Import</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="file" name="file" accept=".xls, .xlsx, .csv" id="input_fileUpload" enctype="multipart/form-data">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-save">Save</button>
      </div>
    </div>
  </div>
</div>

@push('after_scripts')
    <script>
        const MODEL_NAME = $( 'input[name=model-name]' ).val();
        var FIELDS = $( 'input[name=fields]' );
        
        $(function() {
            $('body').on('click', '.btn-save', function() {
                var jform = new FormData();
                jform.append('file', $('#input_fileUpload').get(0).files[0]);
                jform.append('model', MODEL_NAME);
                FIELDS.each(function (key) {
                  jform.append('fields['+key+']', $(this).val());
                })

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: jform,
                    enctype: 'multipart/form-data',
                    url: '/admin/import',
                    success: function (response) {
                        if(response.success) {
                            new Noty({
                                type: 'success',
                                text: response.message
                            }).show();
                            $('.close').click();
                            crud.table.ajax.reload();
                        } else {
                            new Noty({
                                type: 'error',
                                text: response.message
                            }).show();  
                        }
                    },
                    error: function(error) {
                        new Noty({
                                type: 'error',
                                text: "Import failed."
                        }).show();
                    }
                });
            });
        });
        
    </script>

@endpush
