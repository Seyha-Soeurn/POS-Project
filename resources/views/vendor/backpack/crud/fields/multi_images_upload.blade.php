@php
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitUploadMultipleElement';
    $field['wrapper']['data-field-name'] = $field['wrapper']['data-field-name'] ?? $field['name'];
    $field['wrapper']['data-sub-field-name'] = $field['wrapper']['data-sub-field-name'] ?? $field['sub-field'];
@endphp

{{-- upload multiple input --}}
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

	{{-- Show the upload field on CREATE form. --}}
	<div class="backstrap-file mt-2">
		<input
		type="file"
		class="file_input backstrap-file-input"
		multiple
		id="imagesFiles"
		>
		<label class="backstrap-file-label" for="imagesFiles">Upload images</label>
	</div>
	
	<div class="well well-sm existing-file" id="preview-container" style="margin-top: 30px">
		<div class="file-preview">
			@if (isset($field['value']))
				{{-- Show the file name and a "Clear" button on EDIT form. --}}
				@php
					if (is_string($field['value'])) {
						$values = json_decode($field['value'], true) ?? [];
					} else {
						$values = $field['value'];
					}
				@endphp
				@if (count($values))
					@foreach($values as $key => $file)
						<div class="position-relative">
							<a href="{{ URL($file->url) }}" target="_blank">
								<img src="{{ URL($file->url) }}" class="preview-image">
							</a>
							<input type="hidden" name="{{ $field['name'].'['.$key.'][id]' }}" value="{{ $file->id }}" class="store-index" data-index="{{ $key }}">
							<input type="hidden" name="{{ $field['name'].'['.$key.']['.$field['sub-field'].']' }}" value="{{ $file[$field['sub-field']] }}">
							<i class="la la-remove btn-light float-right file-clear-button position-absolute" title="Remove"></i></a>
						</div>
					@endforeach
				@else
					<input name="{{ $field['name'] }}" type="hidden" value="">
				@endif
			@endif
		</div>
	</div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

	@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
	@push('crud_fields_styles')
	@loadOnce('upload_field_styles')
	<style type="text/css">
		.existing-file {
			border-radius: 5px;
			vertical-align: middle;
		}
		.existing-file a {
			display: inline-block;
			font-size: 0.9em;
		}
		.backstrap-file {
			position: relative;
			display: inline-block;
			width: 100%;
			height: calc(1.5em + 0.75rem + 2px);
			margin-bottom: 0;
		}

		.backstrap-file-input {
			position: relative;
			z-index: 2;
			width: 100%;
			height: calc(1.5em + 0.75rem + 2px);
			margin: 0;
			opacity: 0;
		}

		.backstrap-file-input:focus ~ .backstrap-file-label {
			border-color: #acc5ea;
			box-shadow: 0 0 0 0rem rgba(70, 127, 208, 0.25);
		}

		.backstrap-file-input:disabled ~ .backstrap-file-label {
			background-color: #e4e7ea;
		}

		.backstrap-file-input:lang(en) ~ .backstrap-file-label::after {
			content: "Browse";
		}

		.backstrap-file-input ~ .backstrap-file-label[data-browse]::after {
			content: attr(data-browse);
		}

		.backstrap-file-label {
			position: absolute;
			top: 0;
			right: 0;
			left: 0;
			z-index: 1;
			height: calc(1.5em + 0.75rem + 2px);
			padding: 0.375rem 0.75rem;
			font-weight: 400;
			line-height: 1.5;
			color: #5c6873;
			background-color: #fff;
			border: 1px solid #e4e7ea;
			border-radius: 0.25rem;
			font-weight: 400!important;
		}

		.backstrap-file-label::after {
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			z-index: 3;
			display: block;
			height: calc(1.5em + 0.75rem);
			padding: 0.375rem 0.75rem;
			line-height: 1.5;
			color: #5c6873;
			content: "Browse";
			background-color: #f0f3f9;
			border-left: inherit;
			border-radius: 0 0.25rem 0.25rem 0;
		}

		.file-preview {
			display: flex;
			flex-wrap: wrap;
			gap: 5px;
			border-radius: 3px;
			box-sizing: border-box;
		}
		.file-preview .preview-image {
			height: 100px;
			border-radius: 5px;
		}
		.file-clear-button {
			right: 3px;
			top: 3px;
			padding: 4.5px 6px 3.5px 6px;
			border-radius: 2px;
			cursor: pointer;
		}
	</style>
	@endLoadOnce
	@endpush
    @push('crud_fields_scripts')
    	@loadOnce('bpFieldInitUploadMultipleElement')
        <script>
        	function bpFieldInitUploadMultipleElement(element) {
        		var fieldName = element.attr('data-field-name');
        		var subFieldName = element.attr('data-sub-field-name');
        		var clearFileButton = element.find(".file-clear-button");
        		var fileInput = element.find("input[type=file]");
				let maxSize = 5242880;
				let totalSize = 0;
				
		        $('div#preview-container').click(function(e) {
					e.preventDefault();
					let clickEle = e.target;
					if (clickEle.className.includes('file-clear-button')) {
						var container = $(clickEle).parent().parent();
						
						var parent = $(clickEle).parent();
						// remove the filename and button
						parent.remove();
						// create input to enable setXAttribute
						if ($.trim(container.html()) == '') {
							$("<input type='hidden' name='"+fieldName+"'>").appendTo('div.file-preview');
						}
					}
		        });

		        fileInput.change(function() {
					// check if all files are in image type and get size of upload
					let isAllFileAreImage = true;
					let validImageTypes = ["image/gif", "image/jpeg", "image/png", "image/jpg"];
					Array.from($(this)[0].files).forEach(eachFile => {
						totalSize += eachFile['size'];
						if ($.inArray(eachFile['type'], validImageTypes) <= 0) {
							isAllFileAreImage = false;
						}
					});

					if (isAllFileAreImage) {
						if (totalSize <= maxSize) {
							let selectedFiles = [];
	
							Array.from($(this)[0].files).forEach(file => {
								selectedFiles.push({name: file.name, type: file.type})
							});
	
							let uploadedFiles = fileInput[0].files;
							uploadedFiles.forEach((file, index) => {
								// Render to preview image
								let reader = new FileReader();
								reader.readAsDataURL(file);
								reader.onload = e => {
									// Get max index to set position of data
									let maxIndex = 0;
									let indexToUse = maxIndex;
									if ($( 'input.store-index' ).first().length > 0) {
										maxIndex = $( 'input.store-index' ).first().data('index')
										$( 'input.store-index' ).each(function( index, element ) {
											if ($(element).data('index') > maxIndex) {
												maxIndex = $(element).data('index');
											}
										})
										indexToUse = maxIndex+1;
									}
	
									// Create new preview element
									let mainTag = $("<div>").attr('class', 'position-relative').appendTo('div.file-preview');
									let linkImage = $("<a>").attr('href', null).attr('target', '_blank').appendTo(mainTag);
									$("<img>").attr('src', e.target.result).attr('class', 'preview-image').appendTo(linkImage); 
									$("<input>").attr('name', fieldName + '['+(indexToUse)+'][id]').attr("type", "hidden").attr('value', null).attr('class', 'store-index').attr('data-index', (indexToUse)).appendTo(mainTag);
									$("<input>").attr('name', fieldName + '['+(indexToUse)+']['+subFieldName+']').attr("type", "hidden").attr('value', e.target.result).appendTo(mainTag);
									$("<i>").attr("class", "la la-remove btn-light float-right file-clear-button position-absolute").attr('title', 'Remove').appendTo(mainTag);
								}
							});
		
							// remove the hidden input, so that the setXAttribute method is no longer triggered
							$("input[type=hidden][name='"+fieldName+"']").remove();
						} else {
							fileInput.val(null);
							new Noty({
								type: 'error',
								text: 'Upload size must not be greater than 5MB.'
							}).show();
						}
					} else {
						fileInput.val(null);
						new Noty({
							type: 'error',
							text: 'All files must be images.'
						}).show();
					}
		        });
        	}
        </script>
        @endLoadOnce
    @endpush
