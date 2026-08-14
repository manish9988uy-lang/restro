@extends('layouts.app')
@section('title', 'Edit Order '.$order->invoice_no)
@section('page-title', 'Edit Order')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-700">{{ $order->invoice_no }}</span>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
            <form method="POST" action="{{ route('orders.update', $order) }}">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Order Type</label>
                            <select name="order_type" class="form-select">
                                @foreach(['dine_in', 'takeaway', 'delivery'] as $type)
                                <option value="{{ $type }}" @selected($order->order_type == $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Table</label>
                            <select name="table_id" class="form-select">
                                <option value="">—</option>
                                @foreach($tables as $table)
                                <option value="{{ $table->id }}" @selected($order->table_id == $table->id)>{{ $table->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer</label>
                            <select name="customer_id" class="form-select">
                                <option value="">Walk-in</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" @selected($order->customer_id == $customer->id)>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Payment Type</label>
                            <select name="payment_type" class="form-select">
                                @foreach(['cash', 'card', 'online'] as $type)
                                <option value="{{ $type }}" @selected($order->payment_type == $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Scheduled For (optional)</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ $order->scheduled_at?->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $order->notes }}</textarea>
                        </div>
                    </div>

                    <h6 class="mb-3 fw-700">Order Items</h6>
                    <div id="order-items" class="mb-4">
                        @foreach($order->items as $index => $item)
                        <div class="order-item mb-3" data-index="{{ $index }}">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <select name="items[{{ $index }}][menu_item_id]" class="form-select item-select">
                                        @foreach($menuItems as $menuItem)
                                        <option value="{{ $menuItem->id }}" data-price="{{ $menuItem->price }}" @selected($item->menu_item_id == $menuItem->id)>
                                            {{ $menuItem->name }} - ${{ number_format($menuItem->price, 2) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control item-quantity" value="{{ $item->quantity }}" min="1">
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-600 item-subtotal">${{ number_format($item->subtotal, 2) }}</div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger remove-item"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-item" class="btn btn-outline-primary mb-4"><i class="bi bi-plus me-1"></i>Add Item</button>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count($order->items) }};
    
    function calculateSubtotal(element) {
        const itemRow = element.closest('.order-item');
        const select = itemRow.querySelector('.item-select');
        const quantity = parseFloat(itemRow.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
        const subtotal = (price * quantity).toFixed(2);
        itemRow.querySelector('.item-subtotal').textContent = '$' + subtotal;
    }
    
    function addEventListeners(element) {
        element.querySelector('.item-select').addEventListener('change', function() {
            calculateSubtotal(this);
        });
        element.querySelector('.item-quantity').addEventListener('input', function() {
            calculateSubtotal(this);
        });
        element.querySelector('.remove-item').addEventListener('click', function() {
            element.remove();
        });
    }
    
    document.querySelectorAll('.order-item').forEach(addEventListeners);
    
    document.getElementById('add-item').addEventListener('click', function() {
        const container = document.getElementById('order-items');
        const newItem = container.querySelector('.order-item').cloneNode(true);
        newItem.dataset.index = itemIndex;
        newItem.querySelector('[name^="items"]').name = `items[${itemIndex}][menu_item_id]`;
        newItem.querySelector('[name$="[quantity]"]').name = `items[${itemIndex}][quantity]`;
        newItem.querySelector('[name$="[quantity]"]').value = 1;
        container.appendChild(newItem);
        addEventListeners(newItem);
        calculateSubtotal(newItem.querySelector('.item-select'));
        itemIndex++;
    });
});
</script>
@endsection
