@php
    $name = $getName();
    $uploadUrl = $getUploadUrl();
    $placeholder = $getPlaceholder();
    $isConcealed = $isConcealed();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper :valid="$errors->count() === 0">
        <div wire:ignore>
            <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
            <script type="text/javascript">
                function createCKEditor() {
                    CKEDITOR.replace('ckeditor-{{ $name }}', {
                        language: 'vi',
                        height: 500,
                        extraPlugins: 'base64image',
                        // Optional autosave-style hook
                        on: {
                            change: function (evt) {
                                Livewire.dispatch('contentUpdated', {
                                    content: evt.editor.getData(),
                                    editor: 'ckeditor-{{ $name }}'
                                });
                            },
                            paste: function (evt) {
                                setTimeout(function () {
                                    const editor = evt.editor;
                                    const images = editor.document.find('img');
                                    for (let i = 0; i < images.count(); i++) {
                                        const img = images.getItem(i);
                                        img.setAttribute('style', 'display: block; margin: 0 auto; width: 100%; height: auto;');
                                    }
                                }, 100);
                            }
                        },
                        @isset($uploadUrl)
                        filebrowserUploadUrl: '{{ $uploadUrl }}',
                        filebrowserUploadMethod: 'form',
                        @endisset
                    });

                }

            </script>
            <div
                x-data="{
                    state: $wire.$entangle('{{ $getStatePath() }}'),
                    init() {
                        createCKEditor();

                        Livewire.on('contentUpdated', (payload) => {
                            this.state = payload.content;
                        });
                    }
                }"
            >
                <textarea
                    id="ckeditor-{{ $name }}"
                    name="{{ $name }}"
                    x-model="state"
                ></textarea>
            </div>
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
