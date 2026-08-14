@extends('layouts.app')
@section('title', 'Kitchen Display')
@section('page-title', 'Kitchen Display System')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-6 mb-4">
            <h4 class="mb-3 text-primary">
                <i class="bi bi-clock me-2"></i> Pending Orders
            </h4>
            @foreach($orders->where('order_status', 'pending') as $order)
                <div class="card mb-3 border-3 border-warning">
                    <div class="card-header d-flex justify-content-between align-items-center bg-warning bg-opacity-10">
                        <div>
                            <span class="fw-700">Order #{{ $order->invoice_no }}</span>
                            @if($order->table)
                                <span class="ms-2 badge bg-secondary">
                                    Table {{ $order->table->name }}
                                </span>
                            @endif
                        </div>
                        <div class="text-muted small">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <span class="fw-600">{{ $item->quantity }}x</span>
                                    {{ $item->menuItem->name }}
                                </span>
                                @if($item->menuItem->kitchen_station)
                                <span class="badge bg-info text-dark small">
                                    {{ $item->menuItem->kitchen_station }}
                                </span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @if($order->notes)
                        <div class="mt-2 text-muted small">
                            <i class="bi bi-sticky-note me-1"></i> {{ $order->notes }}
                        </div>
                        @endif
                        <div class="mt-3 d-flex justify-content-end">
                            <form method="POST" action="{{ route('kitchen.updateStatus', $order) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="preparing">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-play me-1"></i> Start Preparing
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="col-lg-6 mb-4">
            <h4 class="mb-3 text-info">
                <i class="bi bi-alarm me-2"></i> Preparing Orders
            </h4>
            @foreach($orders->where('order_status', 'preparing') as $order)
                <div class="card mb-3 border-3 border-primary">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary bg-opacity-10">
                        <div>
                            <span class="fw-700">Order #{{ $order->invoice_no }}</span>
                            @if($order->table)
                                <span class="ms-2 badge bg-secondary">
                                    Table {{ $order->table->name }}
                                </span>
                            @endif
                        </div>
                        <div class="text-muted small" id="timer-{{ $order->id }}">
                            @if($order->preparing_at)
                                {{ $order->preparing_at->diffForHumans() }}
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <span class="fw-600">{{ $item->quantity }}x</span>
                                    {{ $item->menuItem->name }}
                                </span>
                                @if($item->menuItem->kitchen_station)
                                <span class="badge bg-info text-dark small">
                                    {{ $item->menuItem->kitchen_station }}
                                </span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @if($order->notes)
                        <div class="mt-2 text-muted small">
                            <i class="bi bi-sticky-note me-1"></i> {{ $order->notes }}
                        </div>
                        @endif
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <form method="POST" action="{{ route('kitchen.updateStatus', $order) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="ready">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Mark as Ready
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
// Auto-refresh the page every 10 seconds
setTimeout(() => location.reload(), 10000);
</script>
@endsection
