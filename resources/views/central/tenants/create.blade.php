<form method="POST" action="{{ route('central.tenants.store') }}">
  @csrf
  <label>ID del tenant</label>
  <input name="id" required>
  <label>Subdominio</label>
  <input name="domain" required>
  <button type="submit">Crear Tenant</button>
</form>
