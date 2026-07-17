@props([
    'name',
    'label' => null,
    'value' => '',
    'required' => false
])

<div class="form-group mb-3">
    @if($label)
        <label for="{{ $name }}" class="font-weight-bold small text-muted mb-1">
            {{ $label }}
            @if($required)<span class="text-danger">*</span>@endif
        </label>
    @endif
    
    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}" 
        class="editor @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    
    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

@once
@push('admin_js')
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    var editor_config = {
        license_key: 'gpl', // Self-hosted GPL license
        selector: 'textarea.editor',
        height: 500,
        base_url: '{{ asset('vendor/tinymce') }}',
        suffix: '.min',
        relative_urls: false,
        convert_urls: false,
        // Free/Open-source plugins only
        plugins: 'anchor autolink charmap code codesample emoticons fullscreen image link lists media preview searchreplace table visualblocks wordcount',
        toolbar: [
            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',
            'link image media table | emoticons charmap | code fullscreen preview | removeformat'
        ],
        toolbar_mode: 'wrap',
        image_caption: true,
        // Laravel FileManager integration
        file_picker_callback: function (callback, value, meta) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = '{{ route('unisharp.lfm.show') }}?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url: cmsURL,
                title: 'Quản lý File',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        },
        content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:16px }',
        branding: false,
        promotion: false,
        menubar: 'file edit view insert format tools table help'
    };
    tinymce.init(editor_config);
</script>
@endpush
@endonce
