@if (session('success') || session('error'))
    <div class="toast-host">
        <div class="toast {{ session('error') ? 'toast--error' : '' }}" role="status">
            <i class="bi {{ session('error') ? 'bi-exclamation-octagon-fill' : 'bi-check-circle-fill' }}"></i>
            <span>{{ session('error') ?? session('success') }}</span>
        </div>
    </div>
@endif
