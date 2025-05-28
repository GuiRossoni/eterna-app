@if (session('success'))
    <div class="alert alert-success auto-hide-alert">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger auto-hide-alert">
        {{ session('error') }}
    </div>
@endif

<script>
    setTimeout(function() {
        document.querySelectorAll('.auto-hide-alert').forEach(function(el) {
            el.style.display = 'none';
        });
    }, 3000);
</script>