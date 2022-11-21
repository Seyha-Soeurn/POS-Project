{{-- phone number input international field --}}
@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? ($field['value'] ?? ($field['default'] ?? ''));
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div class="col-log-12">
        <input
            type="text"
            name="{{ $field['name'] }}"
            id="phone"
            placeholder="Phone number"
            data-init-function="bpFieldInitDummyFieldElement"
            value="{{ $field['value'] }}"
            @include('crud::fields.inc.attributes')
        >
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- CUSTOM CSS --}}
@push('crud_fields_styles')
    {{-- LINK SCRIPT CDN --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js" crossorigin="anonymous"></script>
    {{-- LINK STYLE CDN --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" crossorigin="anonymous" />
    
        <style>
            .phone_field_class {
                display: none;
            }
            .iti {
                width: 100%;
            }
        </style>
    
@endpush

{{-- CUSTOM JS --}}
@push('crud_fields_scripts')
    @loadOnce('bpFieldInitDummyFieldElement')
    <script>
        function bpFieldInitDummyFieldElement(element) {
            console.log(element.value);
            var input = document.querySelector("#phone");
            console.log(input.value);
            window.intlTelInput(input, {
                separateDialCode: true,
            });

            var iti = window.intlTelInputGlobals.getInstance(input);
            input.addEventListener('input', function() { 
            var countryName = iti.getSelectedCountryData().name;
            document.getElementById('country').value = countryName;
            });
        }
    </script>
    @endLoadOnce
@endpush
