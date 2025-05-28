@php
    $impersonateController = new \App\Http\Controllers\ImpersonateController();
    $impersonationInfo = $impersonateController->getImpersonationInfo();
@endphp

@if($impersonationInfo)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 dark:bg-yellow-900/20 dark:border-yellow-500">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                <strong>🎭 Modo Impersonación Activo:</strong> 
                Estás actuando como <strong>{{ $impersonationInfo['impersonated_user']->nombre }} {{ $impersonationInfo['impersonated_user']->apellidos }}</strong>
                desde {{ $impersonationInfo['start_time']->format('d/m/Y H:i:s') }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <form method="POST" action="{{ route('impersonate.stop') }}" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres finalizar la impersonación y volver a tu cuenta de administrador?');">
                @csrf
                <button type="submit" 
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                    🔚 Finalizar
                </button>
            </form>
        </div>
    </div>
</div>
@endif