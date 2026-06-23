@extends('layouts.restaurant')
@section('title','Sales Reports')
@section('page_title','Sales Reports')
@section('content')

<div class="card mb-4">
    <div class="card-body" style="padding:12px 20px;">
        <form method="GET" class="d-flex align-items-end" style="gap:10px;">
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">From Date</label><input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}" style="border-radius:5px;font-size:13px;"></div>
            <div><label style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;">To Date</label><input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}" style="border-radius:5px;font-size:13px;"></div>
            <button type="submit" class="btn btn-sm btn-primary-b2b" style="border-radius:5px;"><i class="fas fa-filter mr-1"></i>Apply</button>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3"><div class="stat-card stat-red"><div class="stat-icon"><i class="fas fa-rupee-sign"></i></div><div><div class="stat-value">₹{{ number_format($totalRevenue,0) }}</div><div class="stat-label">Total Revenue</div></div></div></div>
    <div class="col-md-4 mb-3"><div class="stat-card stat-dark"><div class="stat-icon"><i class="fas fa-clipboard-list"></i></div><div><div class="stat-value">{{ $totalOrders }}</div><div class="stat-label">Total Orders</div></div></div></div>
    <div class="col-md-4 mb-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div><div class="stat-value">₹{{ $totalOrders > 0 ? number_format($totalRevenue/$totalOrders,0) : 0 }}</div><div class="stat-label">Avg Order Value</div></div></div></div>
</div>

<div class="row">
    <div class="col-md-7 mb-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar mr-2" style="color:#e42e0c;"></i>Daily Revenue</div>
            <div class="card-body"><canvas id="dailyChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-trophy mr-2" style="color:#e42e0c;"></i>Top Selling Items</div>
            <div class="card-body" style="padding:0;">
                @forelse($topItems as $i => $item)
                <div class="d-flex align-items-center p-3" style="border-bottom:1px solid #f8f8f8;">
                    <span style="width:24px;height:24px;background:{{ $i===0 ? '#ffd700':($i===1?'#c0c0c0':($i===2?'#cd7f32':'#f0f0f0')) }};border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;margin-right:10px;flex-shrink:0;color:#333;">{{ $i+1 }}</span>
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->food_name }}</p>
                        <p style="margin:0;font-size:11px;color:#888;">{{ $item->total_qty }} sold</p>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#2e7d32;flex-shrink:0;">₹{{ number_format($item->total_revenue,0) }}</span>
                </div>
                @empty
                <div class="text-center p-4"><p style="color:#888;font-size:13px;">No sales data for this period.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
new Chart(document.getElementById('dailyChart'),{
    type:'bar',
    data:{labels:{!! json_encode(array_column($dailyRevenue,'date')) !!},datasets:[{label:'Revenue (₹)',data:{!! json_encode(array_column($dailyRevenue,'revenue')) !!},backgroundColor:'rgba(228,46,12,0.8)',borderRadius:3}]},
    options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'₹'+v}}}}
});
</script>
@endpush
