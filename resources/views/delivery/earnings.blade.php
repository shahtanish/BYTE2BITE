@extends('layouts.delivery')
@section('title','Earnings')
@section('page_title','My Earnings')
@section('content')
<div class="row mb-4">
    <div class="col-md-4 mb-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fas fa-rupee-sign"></i></div><div><div class="stat-value">₹{{ number_format($totalEarnings,0) }}</div><div class="stat-label">Total Earnings</div></div></div></div>
    <div class="col-md-4 mb-3"><div class="stat-card stat-dark"><div class="stat-icon"><i class="fas fa-motorcycle"></i></div><div><div class="stat-value">{{ $earnings->total() }}</div><div class="stat-label">Total Deliveries</div></div></div></div>
    <div class="col-md-4 mb-3"><div class="stat-card stat-red"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div><div class="stat-value">₹{{ $earnings->total() > 0 ? number_format($totalEarnings/$earnings->total(),0) : 0 }}</div><div class="stat-label">Avg Per Delivery</div></div></div></div>
</div>
<div class="card">
    <div class="card-header"><i class="fas fa-wallet mr-2" style="color:#e42e0c;"></i>Earnings History</div>
    <div class="card-body" style="padding:0;">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Amount</th><th>Date</th></tr></thead>
            <tbody>
            @forelse($earnings as $earn)
            <tr>
                <td><strong style="color:#e42e0c;font-size:13px;">{{ $earn->order->order_number ?? '-' }}</strong></td>
                <td style="font-weight:700;color:#2e7d32;font-size:14px;">₹{{ number_format($earn->amount,2) }}</td>
                <td style="font-size:12px;color:#888;">{{ $earn->earned_at ? $earn->earned_at->format('d M Y, h:i A') : $earn->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center" style="padding:30px;color:#888;">No earnings yet. Complete deliveries to earn!</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $earnings->links() }}</div>
@endsection
