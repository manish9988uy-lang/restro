@extends('layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <!-- Total Tenants -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tenants</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTenants }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-building fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Tenants -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Tenants</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeTenants }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-1 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-1 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($totalRevenue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fs-1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Audit Logs -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-history me-2"></i>Recent Audit Logs
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAuditLogs as $log)
                                <tr>
                                    <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No logs found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Tickets Summary -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-ticket-perforated me-2"></i>Support Tickets Summary
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Pending</span>
                            <span class="badge bg-warning rounded-pill">{{ $pendingTickets }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-info"><i class="bi bi-chat-dots me-2"></i>Open</span>
                            <span class="badge bg-info rounded-pill">{{ $openTickets }}</span>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('support-tickets.index') }}" class="btn btn-primary btn-sm w-100">View All Tickets</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Links -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-compass me-2"></i>Quick Links
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('tenants.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-building me-2"></i>Manage Tenants
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-clock-history me-2"></i>Audit Logs
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('support-tickets.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-ticket-perforated me-2"></i>Support Tickets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
