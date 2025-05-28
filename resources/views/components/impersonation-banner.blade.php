@php
    $impersonateController = new \App\Http\Controllers\ImpersonateController();
    $impersonationInfo = $impersonateController->getImpersonationInfo();
    
    // Debug temporal
    if (request()->has('debug')) {
        dd([
            'session_data' => [
                'impersonate_user_id' => session('impersonate_user_id'),
                'original_user_id' => session('original_user_id'),
                'impersonate_start_time' => session('impersonate_start_time')
            ],
            'impersonation_info' => $impersonationInfo,
            'current_user' => auth()->user(),
            'route_exists' => \Route::has('impersonate.stop')
        ]);
    }
@endphp

@if($impersonationInfo)
<div class="bg-yellow-500 text-black p-3 text-center relative z-50 border-b border-yellow-600">
    <div class="flex items-center justify-center space-x-4 flex-wrap">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.768 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <span class="font-semibold">
                🎭 Impersonando a: <strong>{{ $impersonationInfo['impersonated_user']->nombre }} {{ $impersonationInfo['impersonated_user']->apellidos }}</strong>
            </span>
        </div>
        
        <div class="text-sm">
            Desde: {{ $impersonationInfo['start_time']->format('d/m/Y H:i:s') }}
        </div>
        
        <form method="POST" action="{{ route('impersonate.stop') }}" class="inline">
            @csrf
            <button type="submit" 
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded text-sm font-medium transition-colors shadow-sm"
                    onclick="console.log('Formulario enviándose a:', '{{ route('impersonate.stop') }}'); return confirm('¿Estás seguro de que quieres finalizar la impersonación?');">
                🔚 Finalizar Impersonación
            </button>
        </form>
    </div>
</div>
@endif