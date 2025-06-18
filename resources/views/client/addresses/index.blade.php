@extends('layouts.app')

@section('title', __('messages.addresses'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('messages.addresses') }}</h1>
        <a href="{{ route('addresses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            {{ __('messages.add_new_address') }}
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">{{ __('messages.no_addresses') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $address)
                <div class="bg-white rounded-lg shadow p-6 {{ $address->is_default ? 'border-2 border-blue-500' : '' }}">
                    @if($address->is_default)
                        <div class="mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ __('messages.default_address') }}
                            </span>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h3 class="font-semibold">{{ $address->full_name }}</h3>
                        <p class="text-gray-600">{{ $address->phone }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-700">{{ $address->full_address }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div class="space-x-2">
                            <a href="{{ route('addresses.edit', $address) }}" class="text-blue-500 hover:text-blue-700">
                                {{ __('messages.edit') }}
                            </a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('{{ __('messages.delete_address_confirm') }}')">
                                    {{ __('messages.delete') }}
                                </button>
                            </form>
                        </div>
                        
                        @if(!$address->is_default)
                            <form action="{{ route('addresses.default', $address) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-blue-500 hover:text-blue-700">
                                    {{ __('messages.set_as_default') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection 