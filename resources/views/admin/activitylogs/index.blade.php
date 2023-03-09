<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activitylogs') }}
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <x-splade-table :for="$activitylogs" striped class="py-6">
                            @cell('subject_type', $log)
                                {{  class_basename($log->subject_type) }}
                            @endcell
                            @cell('subject_name', $log)
                                {{  $log->subject->name ?? $log->subject->title ?? ($log->subject_type . '::' . $log->subject_id) ?? '' }}
                            @endcell
                            @cell('attributes', $log)
                                @foreach($log->properties['attributes'] ?? [] as $attribute => $new)
                                    @if($new != ($log->properties['old'][$attribute] ?? false) && !in_array($attribute, ['created_at', 'updated_at', 'deleted_at']))
                                        @if(is_string($new) || is_numeric($new))
                                            <div class="flex">
                                                <div class="w-40">{{ $attribute }}: </div>
                                                <div class="w-40">{!! ($log->properties['old'][$attribute] ?? '<i>(leeg)</i>') !!}</div>
                                                <div class="w-10">=></div>
                                                <div class="w-40">{!! ($new ?? '<i>(leeg)</i>') !!}</div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach

                                @if($log->event === 'deleted')
                                    @foreach($log->properties['old'] ?? [] as $attribute => $old)
                                        @if($old != ($log->properties['attributes'][$attribute] ?? false) && !in_array($attribute, ['created_at', 'updated_at', 'deleted_at']))
                                            @if(is_string($old) || is_numeric($old))
                                                <div class="flex">
                                                    <div class="w-40">{{ $attribute }}: </div>
                                                    <div class="w-40">{!! ($old ?? '<i>(leeg)</i>') !!}</div>
                                                    <div class="w-10">=></div>
                                                    <div class="w-40">{!! ($log->properties['attributes'][$attribute] ?? '<i>(leeg)</i>') !!}</div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                                <br />
                                <div class="text-center">
                                    <Link href="#info-{{ $log->id }}">
                                        [{{ __('Details') }}]
                                    </Link>
                                </div>

                                <x-splade-modal name="info-{{ $log->id }}" max-width="7xl">
                                    @foreach($log->properties['attributes'] ?? [] as $attribute => $new)
                                        @if($new != ($log->properties['old'][$attribute] ?? false) && !in_array($attribute, ['created_at', 'updated_at', 'deleted_at']))
                                            @if(is_string($new) || is_numeric($new))
                                                <div class="flex">
                                                    <div class="w-48">{{ $attribute }}: </div>
                                                    <div class="w-96">{!! ($log->properties['old'][$attribute] ?? '<i>(leeg)</i>') !!}</div>
                                                    <div class="w-6">=></div>
                                                    <div class="w-96">{!! ($new ?? '<i>(leeg)</i>') !!}</div>
                                                </div>
                                            @else
                                                {{ $attribute . ':' }}
                                                <pre>Oud: {{ print_r($log->properties['old'][$attribute] ?? [], true) }}</pre>
                                                =>
                                                <pre>Nieuw: {{ print_r($new ?? [], true) }}</pre>
                                                <hr />
                                            @endif
                                        @endif
                                    @endforeach

                                    @if($log->event === 'deleted')
                                        @foreach($log->properties['old'] ?? [] as $attribute => $old)
                                            @if($old != ($log->properties['attributes'][$attribute] ?? false) && !in_array($attribute, ['created_at', 'updated_at', 'deleted_at']))
                                                <pre>{{ $attribute }}: {{ print_r($old ?? [], true) }}</pre>
                                            @endif
                                        @endforeach
                                    @endif
                                </x-splade-modal>
                            @endcell
                        </x-splade-table>

                    </div>
                </div>
            </div>
        </div>
    </x-splade-modal>
</x-app-layout>
