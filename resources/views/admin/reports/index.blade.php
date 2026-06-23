@extends('layouts.admin')
@section('title','Reports')
@section('page_title','Reports & Analytics')
@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="stat-card stat-red"><div class="stat-icon"><i class="fas fa-rupee-sign"></i></div><div><div class="stat-value">₹{{ number_format($totalRevenue,0) }}</div><div class="stat-label">Total Revenue</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-dark"><div class="stat-icon"><i class="fas fa-clipboard-list"></i></div><div><div class="stat-value">{{ number_format($totalOrders) }}</div><div class="stat-label">Total Orders</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-blue"><div class="stat-icon"><i class="fas fa-users"></i></div><div><div class="stat-value">{{ number_format($totalCustomers) }}</div><div class="stat-label">Customers</div></div></div></div>
    <div class="col-md-3 mb-3"><div class="stat-card stat-green"><div class="stat-icon"><i class="fas fa-store"></i></div><div><div class="stat-value">{{ number_format($totalRestaurants) }}</div><div class="stat-label">Restaurants</div></div></div></div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar mr-2" style="color:#e42e0c;"></i>Monthly Revenue & Orders (Last 12 Months)</div>
            <div class="card-body"><canvas id="monthlyChart" height="100"></canvas></div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-trophy mr-2" style="color:#e42e0c;"></i>Top Restaurants by Revenue</div>
            <div class="card-body" style="padding:0;">
                @foreach($topRestaurants as $i => $rest)
                <div class="d-flex align-items-center p-3" style="border-bottom:1px solid #f8f8f8;">
                    <span style="width:24px;height:24px;background:{{ $i===0 ? '#ffd700' : ($i===1 ? '#c0c0c0' : ($i===2 ? '#cd7f32' : '#f0f0f0')) }};border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;margin-right:10px;flex-shrink:0;">{{ $i+1 }}</span>
                    <img src="{{ $rest->logo_url }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:10px;flex-shrink:0;">
                    <div style="flex:1;min-width:0;">
                        <p style="margin:0;font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $rest->name }}</p>
                        <p style="margin:0;font-size:11px;color:#888;">{{ $rest->city }}</p>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#2e7d32;flex-shrink:0;margin-left:10px;">₹{{ number_format($rest->revenue ?? 0,0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
<script>
var labels  = {!! json_encode(array_column($monthlyRevenue,'month')) !!};
var revenue = {!! json_encode(array_column($monthlyRevenue,'revenue')) !!};
var orders  = {!! json_encode(array_column($monthlyRevenue,'orders')) !!};
new Chart(document.getElementById('monthlyChart'), {
    type:'bar',
    data:{
        labels: labels,
        datasets:[
            {label:'Revenue (₹)',data:revenue,backgroundColor:'rgba(228,46,12,0.8)',borderRadius:4,yAxisID:'y'},
            {label:'Orders',data:orders,type:'line',borderColor:'#252525',backgroundColor:'rgba(37,37,37,0.1)',pointRadius:4,fill:true,yAxisID:'y1'}
        ]
    },
    options:{responsive:true,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,ticks:{callback:v=>'₹'+v}},y1:{beginAtZero:true,position:'right',grid:{drawOnChartArea:false}}}}
});
</script>
@endpush
