<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Grupos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Buscador -->
                <div class="mb-4">
                    <form action="{{ route('asignaturas.grupos') }}" method="GET">
                        <div class="flex gap-2">
                            <input type="text" name="search" placeholder="Buscar asignatura..." 
                                value="{{ request('search') }}"
                                class="border rounded px-4 py-2 flex-grow">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Buscar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Listado de asignaturas por titulación -->
                @foreach ($asignaturas->groupBy('titulacion.nombre_titulacion') as $titulacion => $asignaturasGrupo)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2 bg-gray-100 p-2 rounded">
                            {{ $titulacion }}
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-700">
                                        <th class="py-2 px-4 text-left">Estado</th>
                                        <th class="py-2 px-4 text-left">Nombre Asignatura</th>
                                        <th class="py-2 px-4 text-center">Curso</th>
                                        <th class="py-2 px-4 text-left">Grupos</th>
                                        <th class="py-2 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asignaturasGrupo as $asignatura)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-2 px-4">{{ $asignatura->estado }}</td>
                                            <td class="py-2 px-4">{{ $asignatura->nombre_asignatura }}</td>
                                            <td class="py-2 px-4 text-center">{{ $asignatura->curso }}º</td>
                                            <td class="py-2 px-4">
                                                <form action="{{ route('asignaturas.reasignar-grupos', $asignatura->id_asignatura) }}" 
                                                    method="POST" id="form-reasignar-{{ $asignatura->id_asignatura }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <div class="grupos-container" id="grupos-{{ $asignatura->id_asignatura }}">
                                                        <!-- Contenedor para los grupos de teoría -->
                                                        <div class="flex flex-wrap gap-4 mb-4" id="grupos-teoria-{{ $asignatura->id_asignatura }}">
                                                            @for ($i = 1; $i <= $asignatura->grupos_teoria; $i++)
                                                                <div class="border rounded-lg p-3 bg-gray-50 flex-grow">
                                                                    <div class="bg-blue-100 rounded p-2 mb-2 text-center font-medium">
                                                                        Grupo Teoría {{ $i }}
                                                                    </div>
                                                                    <!-- Contenedor para grupos de práctica de este grupo de teoría -->
                                                                    <div class="min-h-[100px] border border-dashed border-gray-300 rounded p-2 grupo-practica-dropzone"
                                                                        data-teoria="{{ $i }}" 
                                                                        data-asignatura="{{ $asignatura->id_asignatura }}">
                                                                        
                                                                        @foreach($asignatura->distribucion_grupos->where('grupo_teoria', $i) as $distribucion)
                                                                            @for ($j = 1; $j <= $distribucion->total_practicas; $j++)
                                                                                <div class="grupo-practica-item bg-green-100 rounded p-2 mb-2 cursor-move"
                                                                                    data-practica="{{ $j }}" 
                                                                                    data-teoria-actual="{{ $i }}"
                                                                                    draggable="true">
                                                                                    Práctica {{ $j }}
                                                                                    <input type="hidden" 
                                                                                        name="grupos[{{ $i }}][]" 
                                                                                        value="{{ $j }}">
                                                                                </div>
                                                                            @endfor
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                        
                                                        <div class="flex justify-end mt-2">
                                                            <button type="submit" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                                                Guardar cambios
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                            
                                            <td class="py-2 px-4 text-center">
                                                <a href="{{ route('asignaturas.show', $asignatura->id_asignatura) }}" 
                                                    class="text-blue-500 hover:text-blue-700">
                                                    Ver 🔎
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- JavaScript para manejar el drag and drop -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Obtenemos todos los elementos arrastrables
    const draggables = document.querySelectorAll('.grupo-practica-item');
    const dropzones = document.querySelectorAll('.grupo-practica-dropzone');
    
    // Configuramos los eventos para elementos arrastrables
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', function(e) {
            draggable.classList.add('bg-gray-300');
            e.dataTransfer.setData('text/plain', JSON.stringify({
                practica: draggable.dataset.practica,
                teoriaActual: draggable.dataset.teoriaActual,
                asignatura: draggable.closest('.grupos-container').id.split('-')[1]
            }));
        });
        
        draggable.addEventListener('dragend', function() {
            draggable.classList.remove('bg-gray-300');
        });
    });
    
    // Configuramos los eventos para las zonas de destino
    dropzones.forEach(dropzone => {
        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('border-blue-500');
        });
        
        dropzone.addEventListener('dragleave', function() {
            dropzone.classList.remove('border-blue-500');
        });
        
        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500');
            
            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
            const asignaturaId = data.asignatura;
            const grupoTeoriaAnterior = data.teoriaActual;
            const grupoTeoriaNuevo = dropzone.dataset.teoria;
            
            // Solo procesamos si el destino es diferente al origen
            if (grupoTeoriaAnterior !== grupoTeoriaNuevo && asignaturaId === dropzone.dataset.asignatura) {
                // Encontrar el elemento original
                const elementoOriginal = document.querySelector(`.grupo-practica-item[data-practica="${data.practica}"][data-teoria-actual="${grupoTeoriaAnterior}"]`);
                
                if (elementoOriginal) {
                    // Eliminar del grupo anterior primero para evitar problemas de duplicación
                    const dropzoneAnterior = document.querySelector(`.grupo-practica-dropzone[data-teoria="${grupoTeoriaAnterior}"][data-asignatura="${asignaturaId}"]`);
                    elementoOriginal.remove();
                    
                    // Renumerar los grupos de práctica en el grupo de teoría anterior
                    // Lo hacemos después de quitar el elemento, pero antes de crear el nuevo
                    if (dropzoneAnterior) {
                        renumerarGruposPractica(dropzoneAnterior);
                    }
                    
                    // Contar cuántos grupos de práctica hay actualmente en este grupo de teoría
                    const gruposPracticaActuales = dropzone.querySelectorAll('.grupo-practica-item').length;
                    
                    // El nuevo grupo será el siguiente número
                    const nuevaNumPractica = gruposPracticaActuales + 1;
                    
                    // Crear un nuevo elemento con el número correcto
                    const nuevoElemento = document.createElement('div');
                    nuevoElemento.className = 'grupo-practica-item bg-green-100 rounded p-2 mb-2 cursor-move';
                    nuevoElemento.dataset.practica = nuevaNumPractica;
                    nuevoElemento.dataset.teoriaActual = grupoTeoriaNuevo;
                    nuevoElemento.draggable = true;
                    
                    // Usamos textContent para evitar problemas con los nodos
                    nuevoElemento.textContent = `Práctica ${nuevaNumPractica}`;
                    
                    // Crear el input hidden para el nuevo grupo
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `grupos[${grupoTeoriaNuevo}][]`;
                    hiddenInput.value = nuevaNumPractica;
                    nuevoElemento.appendChild(hiddenInput);
                    
                    // Añadir al nuevo contenedor
                    dropzone.appendChild(nuevoElemento);
                    
                    // Configurar eventos para el nuevo elemento
                    configurarEventosDragDrop(nuevoElemento);
                }
            }
        });
    });
    
    function renumerarGruposPractica(dropzone) {
        if (!dropzone) return;
        
        const gruposPractica = Array.from(dropzone.querySelectorAll('.grupo-practica-item'));
        const grupoTeoria = dropzone.dataset.teoria;
        
        // Limpiamos primero cualquier texto suelto que pueda estar causando problemas
        // Esto evita la duplicación de textos "Práctica X"
        gruposPractica.forEach(grupo => {
            // Guardamos solo el input hidden
            const input = grupo.querySelector('input[type="hidden"]');
            
            // Limpiamos todo el contenido
            grupo.innerHTML = '';
            
            // Si había un input, lo volvemos a añadir
            if (input) {
                grupo.appendChild(input);
            }
        });
        
        // Ahora renumeramos correctamente
        gruposPractica.forEach((grupo, index) => {
            // Actualizar el número de práctica
            const numPractica = index + 1;
            grupo.dataset.practica = numPractica;
            
            // Actualizar el texto visible (ahora como un nodo de texto nuevo)
            const textoNodo = document.createTextNode(`Práctica ${numPractica}`);
            grupo.insertBefore(textoNodo, grupo.firstChild);
            
            // Actualizar el valor del input hidden
            const input = grupo.querySelector('input[type="hidden"]');
            if (input) {
                input.name = `grupos[${grupoTeoria}][]`;
                input.value = numPractica;
            }
        });
    }
    
    function configurarEventosDragDrop(elemento) {
        elemento.addEventListener('dragstart', function(e) {
            elemento.classList.add('bg-gray-300');
            e.dataTransfer.setData('text/plain', JSON.stringify({
                practica: elemento.dataset.practica,
                teoriaActual: elemento.dataset.teoriaActual,
                asignatura: elemento.closest('.grupos-container').id.split('-')[1]
            }));
        });
        
        elemento.addEventListener('dragend', function() {
            elemento.classList.remove('bg-gray-300');
        });
    }
});
    </script>
</x-app-layout>