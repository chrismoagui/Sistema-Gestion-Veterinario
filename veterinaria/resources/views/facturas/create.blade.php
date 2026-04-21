@extends('layouts.app')
@section('title', 'Nueva Factura')
@section('page-title', 'Generar Factura')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
    <div class="card-header"><i class="bi bi-receipt me-2"></i>Nueva Factura</div>
    <div class="card-body">
        <form method="POST" action="{{ route('facturas.store') }}" id="formFactura">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cita asociada *</label>
                    <select name="cita_id" class="form-select" required>
                        <option value="">Seleccionar cita...</option>
                        @foreach($citas as $c)
                        <option value="{{ $c->id }}" data-cliente="{{ $c->paciente->cliente_id }}"
                            {{ ($cita && $cita->id == $c->id) ? 'selected' : '' }}>
                            Cita #{{ $c->id }} — {{ $c->paciente->nombre }} — {{ $c->fecha_hora->format('d/m/Y') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Cliente *</label>
                    <select name="cliente_id" class="form-select" required>
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ ($cita && $cita->paciente->cliente_id == $c->id) ? 'selected' : '' }}>
                            {{ $c->nombre_completo }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Fecha de emisión *</label>
                    <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Fecha de vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Descuento ($)</label>
                    <input type="number" name="descuento" id="descuento" class="form-control" step="0.01" min="0" value="0">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold small">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <!-- Detalles -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Ítems de la factura</h6>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addItem">
                    <i class="bi bi-plus-lg me-1"></i>Agregar ítem
                </button>
            </div>
            <div id="detalles">
                <div class="row g-2 detalle-row align-items-end mb-2">
                    <div class="col-md-5">
                        <input type="text" name="detalles[0][descripcion]" class="form-control" placeholder="Descripción del servicio" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="detalles[0][cantidad]" class="form-control cantidad" value="1" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="detalles[0][precio_unitario]" class="form-control precio" step="0.01" min="0" placeholder="Precio" required>
                    </div>
                    <div class="col-md-1 text-end fw-semibold subtotal-item">$0.00</div>
                    <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-trash"></i></button></div>
                </div>
            </div>

            <div class="border-top pt-3 mt-3">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal:</span> <strong id="show-subtotal">$0.00</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Descuento:</span> <strong id="show-descuento">$0.00</strong></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">IVA (19%):</span> <strong id="show-iva">$0.00</strong></div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-1"><span class="fw-bold">TOTAL:</span> <strong class="text-primary fs-5" id="show-total">$0.00</strong></div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-2"></i>Generar Factura
                </button>
                <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
let idx = 1;
document.getElementById('addItem').addEventListener('click', () => {
    const div = document.createElement('div');
    div.className = 'row g-2 detalle-row align-items-end mb-2';
    div.innerHTML = `
        <div class="col-md-5"><input type="text" name="detalles[${idx}][descripcion]" class="form-control" placeholder="Descripción" required></div>
        <div class="col-md-2"><input type="number" name="detalles[${idx}][cantidad]" class="form-control cantidad" value="1" min="1" required></div>
        <div class="col-md-3"><input type="number" name="detalles[${idx}][precio_unitario]" class="form-control precio" step="0.01" min="0" placeholder="Precio" required></div>
        <div class="col-md-1 text-end fw-semibold subtotal-item">$0.00</div>
        <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-trash"></i></button></div>`;
    document.getElementById('detalles').appendChild(div);
    idx++;
    bindEvents();
});

function bindEvents() {
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.onclick = () => { if (document.querySelectorAll('.detalle-row').length > 1) btn.closest('.detalle-row').remove(); calcTotals(); };
    });
    document.querySelectorAll('.cantidad, .precio').forEach(i => { i.oninput = calcTotals; });
}

function calcTotals() {
    let subtotal = 0;
    document.querySelectorAll('.detalle-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.cantidad').value) || 0;
        const price = parseFloat(row.querySelector('.precio').value) || 0;
        const sub = qty * price;
        row.querySelector('.subtotal-item').textContent = '$' + sub.toFixed(2);
        subtotal += sub;
    });
    const descuento = parseFloat(document.getElementById('descuento').value) || 0;
    const iva = (subtotal - descuento) * 0.19;
    const total = subtotal - descuento + iva;
    document.getElementById('show-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('show-descuento').textContent = '$' + descuento.toFixed(2);
    document.getElementById('show-iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('show-total').textContent = '$' + total.toFixed(2);
}
document.getElementById('descuento').addEventListener('input', calcTotals);
bindEvents();
</script>
@endpush
