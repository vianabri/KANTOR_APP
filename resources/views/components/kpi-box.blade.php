<div class="col">
    <div class="card shadow-sm border-0 text-center h-100">
        <div class="card-body p-3">
            <h6 class="text-muted mb-1">{{ $label }}</h6>
            <h4 class="fw-bold {{ $class ?? 'text-dark' }}">
                {{ $value }}
            </h4>
            @isset($small)
                <small class="text-muted">{{ $small }}</small>
            @endisset
        </div>
    </div>
</div>
