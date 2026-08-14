@extends('layouts.app')
@section('title', 'Floor Designer')
@section('page-title', 'Floor Designer')

@section('content')
<div class="row g-4">
    <div class="col-lg-3">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Instructions</div>
            <div class="card-body">
                <ul class="mb-0 small text-muted">
                    <li>Drag tables to rearrange them</li>
                    <li>Click a table to edit details</li>
                    <li>Double-click to resize (hold)</li>
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="bi bi-list-check me-2"></i>Tables</div>
            <div class="card-body p-2">
                <a href="{{ route('tables.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
                @foreach($tables as $table)
                    <div class="small mb-2 p-2 rounded {{ $table->status == 'available' ? 'bg-success-subtle text-success' : ($table->status == 'occupied' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                        <strong>{{ $table->name }}</strong><br>
                        <span class="text-muted">{{ $table->capacity }} seats</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-buildings me-2"></i>Floor Plan</span>
                <button id="saveLayoutBtn" class="btn btn-sm btn-primary"><i class="bi bi-save me-1"></i>Save Layout</button>
            </div>
            <div class="card-body">
                <div id="floorContainer" class="border rounded bg-light" style="height: 600px; position: relative; overflow: auto;">
                    @foreach($tables as $table)
                    <div class="floor-table position-absolute d-flex align-items-center justify-content-center text-center rounded-3 shadow-sm cursor-move"
                         data-table-id="{{ $table->id }}"
                         style="left: {{ $table->position_x }}px; top: {{ $table->position_y }}px; width: {{ $table->width }}px; height: {{ $table->height }}px;
                                {{ $table->shape == 'circle' ? 'border-radius: 50%;' : '' }}
                                {{ $table->status == 'available' ? 'background-color: #d1fae5; color: #065f46; border: 2px solid #10b981;' : '' }}
                                {{ $table->status == 'occupied' ? 'background-color: #fee2e2; color: #991b1b; border: 2px solid #ef4444;' : '' }}
                                {{ $table->status == 'reserved' ? 'background-color: #fef3c7; color: #92400e; border: 2px solid #f59e0b;' : '' }}
                                {{ $table->status == 'maintenance' ? 'background-color: #f3f4f6; color: #374151; border: 2px solid #9ca3af;' : '' }}">
                        <div>
                            <div class="fw-700">{{ $table->name }}</div>
                            <div class="small"><i class="bi bi-people"></i> {{ $table->capacity }}</div>
                            @if($table->active_orders > 0)
                                <div class="small"><i class="bi bi-receipt"></i> {{ $table->active_orders }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let draggedTable = null;
    let isDragging = false;
    let startX, startY, initialX, initialY;

    document.querySelectorAll('.floor-table').forEach(table => {
        table.addEventListener('mousedown', startDrag);
        table.addEventListener('dblclick', function() {
            window.location.href = "{{ route('tables.index') }}";
        });
    });

    function startDrag(e) {
        draggedTable = e.target.closest('.floor-table');
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        initialX = parseInt(draggedTable.style.left) || 0;
        initialY = parseInt(draggedTable.style.top) || 0;
        draggedTable.style.zIndex = 1000;
        document.addEventListener('mousemove', drag);
        document.addEventListener('mouseup', stopDrag);
    }

    function drag(e) {
        if (!isDragging || !draggedTable) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        draggedTable.style.left = Math.max(0, initialX + dx) + 'px';
        draggedTable.style.top = Math.max(0, initialY + dy) + 'px';
    }

    function stopDrag() {
        if (draggedTable) {
            draggedTable.style.zIndex = 1;
        }
        isDragging = false;
        document.removeEventListener('mousemove', drag);
        document.removeEventListener('mouseup', stopDrag);
    }

    document.getElementById('saveLayoutBtn').addEventListener('click', function() {
        const updates = [];
        document.querySelectorAll('.floor-table').forEach(table => {
            const id = table.dataset.tableId;
            const position_x = parseInt(table.style.left);
            const position_y = parseInt(table.style.top);
            const width = parseInt(table.style.width);
            const height = parseInt(table.style.height);
            updates.push({ id, position_x, position_y, width, height });
        });

        // Save all tables one by one (or in bulk)
        Promise.all(updates.map(async (data) => {
            await fetch("{{ url('/tables') }}/" + data.id + "/position", {
                method: "PUT",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify(data)
            });
        })).then(() => {
            alert('Layout saved!');
        });
    });
</script>
@endsection
