<!-- resources/views/components/database-selector.blade.php -->
<div class="flex items-center space-x-2 p-2 bg-gray-100 rounded">
    <span class="text-sm font-medium text-gray-700">Base de datos:</span>
    <form action="{{ route('cambiar.base.datos') }}" method="POST">
        @csrf
        <select name="connection" onchange="this.form.submit()" class="rounded border-gray-300 text-sm">
            <option value="mysql" {{ $currentConnection == 'mysql' ? 'selected' : '' }}>Curso actual</option>
            <option value="mysql_proximo" {{ $currentConnection == 'mysql_proximo' ? 'selected' : '' }}>Próximo curso</option>
        </select>
    </form>
</div>