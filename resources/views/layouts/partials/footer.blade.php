<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BOOTSTRAP 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- ADMINLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Form Logout Tersembunyi --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
// Fungsi global untuk logout
function logout(event) {
    event.preventDefault();
    document.getElementById('logout-form').submit();
}

// Debug: cek apakah Bootstrap dan jQuery terload
$(document).ready(function() {
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap version:', ($.fn.modal ? '4.x' : 'Not Found'));
});
</script>