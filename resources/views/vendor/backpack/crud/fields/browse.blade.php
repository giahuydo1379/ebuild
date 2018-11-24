<!-- browse server input -->

<div @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
	<div class="load-image-{{ $field['name'] }}">
		@if(isset($field['value']))
			<div class="img-thumbnail box-image" style="background: url('{{ $field['value'] }}') no-repeat center;">
			</div>
		@endif
	</div>
	<input
		type="hidden"
		id="{{ $field['name'] }}-filemanager"

		name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')

		@if(!isset($field['readonly']) || $field['readonly']) readonly @endif
	>

	<div class="btn-group" role="group" aria-label="..." style="margin-top: 3px;">
	  <button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-default popup_selector">
		<i class="fa fa-cloud-upload"></i> {{ trans('backpack::crud.browse_uploads') }}</button>
		<button type="button" data-inputid="{{ $field['name'] }}-filemanager" class="btn btn-default clear_elfinder_picker">
		<i class="fa fa-eraser"></i> {{ trans('backpack::crud.clear') }}</button>
	</div>

	@if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

	{{-- FIELD CSS - will be loaded in the after_styles section --}}
	@push('crud_fields_styles')
		<!-- include browse server css -->
		<link href="{{ asset('vendor/backpack/colorbox/example2/colorbox.css') }}" rel="stylesheet" type="text/css" />
		<style>
			#cboxContent, #cboxLoadedContent, .cboxIframe {
				background: transparent;
			}

			.box-image {
				margin-right: 5px; 
				width: 150px; 
				height: 150px; 
				background-size: cover !important;
			}
		</style>
	@endpush

	@push('crud_fields_scripts')
		<!-- include browse server js -->
		<script src="{{ asset('vendor/backpack/colorbox/jquery.colorbox-min.js') }}"></script>
	@endpush

@endif

{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
	<script>
        jQuery('document').ready(function($){
            @if (isset($field['value']))
            var image = $("[name=image_url]").val()+'{{ $field['value'] }}';
            @else
            var image = '/images/default.jpg';
            @endif

            $('.load-image-{{ $field['name'] }}').html('<div class="img-thumbnail box-image" style="background: url('+image+') no-repeat center;"></div>');
        });
		$(document).on('click','.popup_selector[data-inputid={{ $field['name'] }}-filemanager]',function (event) {
		    event.preventDefault();

		    // trigger the reveal modal with elfinder inside
		    var triggerUrl = "{{ url(config('elfinder.route.prefix').'/popup/'.$field['name']."-filemanager") }}";

		    $.colorbox({
		        href: triggerUrl,
		        fastIframe: true,
		        iframe: true,
		        width: '80%',
		        height: '80%'
		    });
		});

		// function to update the file selected by elfinder
		function processSelectedFile(filePath, requestingField) {
		    //$('#' + requestingField).val(filePath);
			filePath = '/'+filePath.replace(/\\/g, '/');

            var field_name = requestingField.replace("-filemanager", "");

			$('.load-image-' + field_name)
        	.html("<div class='img-thumbnail box-image' style='background: url(\""+filePath+"\") no-repeat center;'><a href='javascript:void(0)' class='btn btn-xs btn-danger'><i class='fa fa-close'></i></a></div>");

			$("[name=" + field_name + "]").val(filePath);

            field_name = "image_url";
			$("[name=" + field_name + "]").val('<?=config('app.url_outside')?>');
		}

		$(document).on('click','.clear_elfinder_picker[data-inputid={{ $field['name'] }}-filemanager]',function (event) {
		    event.preventDefault();
		    var updateID = $(this).attr('data-inputid'); // Btn id clicked
		    $("#"+updateID).val("");
		});
	</script>
@endpush

{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}