<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Invoice #{{ $order->order_number }}</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0;padding:0;box-sizing:border-box; }
body { font-family:'Poppins',sans-serif;color:#333;background:#fff;font-size:13px; }
.invoice-wrap { max-width:700px;margin:30px auto;padding:40px;border:1px solid #eee; }
.header { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:30px;padding-bottom:20px;border-bottom:2px solid #e42e0c; }
.brand { font-size:24px;font-weight:700;color:#252525; }
.brand em { color:#e42e0c;font-style:normal; }
.invoice-no { text-align:right; }
.invoice-no h5 { color:#e42e0c;font-size:18px;margin-bottom:5px; }
.section-title { font-weight:700;color:#e42e0c;font-size:12px;text-transform:uppercase;letter-spacing:1px;margin:20px 0 10px; }
table { width:100%;border-collapse:collapse; }
th { background:#252525;color:#fff;padding:8px 12px;text-align:left;font-size:12px; }
td { padding:8px 12px;border-bottom:1px solid #f0f0f0;font-size:13px; }
tr:nth-child(even) td { background:#f9f9f9; }
.totals { margin-top:20px;border-top:2px solid #eee;padding-top:15px; }
.totals .row { display:flex;justify-content:space-between;padding:4px 0;font-size:13px; }
.totals .grand { font-size:16px;font-weight:700;color:#e42e0c;border-top:2px solid #e42e0c;margin-top:8px;padding-top:8px; }
.footer { margin-top:30px;padding-top:20px;border-top:1px solid #eee;text-align:center;color:#aaa;font-size:12px; }
@media print { body { -webkit-print-color-adjust:exact; } }
</style>
</head>
<body>
<div class="invoice-wrap">
    <div class="header">
        <div>
            <div class="brand">BYTE<em>2BITE</em></div>
            <p style="color:#888;font-size:12px;margin-top:5px;">Multi-Vendor Food Delivery</p>
            <p style="color:#888;font-size:12px;">hello@byte2bite.com</p>
        </div>
        <div class="invoice-no">
            <h5>INVOICE</h5>
            <p><strong>{{ $order->order_number }}</strong></p>
            <p style="color:#888;">{{ $order->created_at->format('d M Y') }}</p>
            <p style="margin-top:8px;padding:4px 12px;border-radius:3px;display:inline-block;font-size:11px;font-weight:700;background:{{ $order->status==='delivered' ? '#e8f5e9' : '#fff3e0' }};color:{{ $order->status==='delivered' ? '#2e7d32' : '#e65100' }};">{{ strtoupper($order->status) }}</p>
        </div>
    </div>

    <div style="display:flex;gap:40px;margin-bottom:25px;">
        <div>
            <p class="section-title">Bill To</p>
            <p><strong>{{ $order->user->name }}</strong></p>
            <p style="color:#555;">{{ $order->user->email }}</p>
            <p style="color:#555;">{{ $order->user->phone }}</p>
        </div>
        <div>
            <p class="section-title">Deliver To</p>
            <p><strong>{{ $order->delivery_name }}</strong></p>
            <p style="color:#555;">{{ $order->delivery_phone }}</p>
            <p style="color:#555;">{{ $order->delivery_address }}, {{ $order->delivery_city }}</p>
        </div>
        <div>
            <p class="section-title">Payment</p>
            <p><strong>{{ strtoupper($order->payment_method) }}</strong></p>
            <p style="color:#555;">Status: {{ ucfirst($order->payment_status) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr><th>Item</th><th>Restaurant</th><th>Qty</th><th>Price</th><th>Total</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->food_name }}</td>
                <td>{{ $item->restaurant->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ number_format($item->food_price,2) }}</td>
                <td>₹{{ number_format($item->subtotal,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="row"><span>Subtotal</span><span>₹{{ number_format($order->subtotal,2) }}</span></div>
        <div class="row"><span>Delivery Fee</span><span>₹{{ number_format($order->delivery_fee,2) }}</span></div>
        <div class="row"><span>Tax (5%)</span><span>₹{{ number_format($order->tax,2) }}</span></div>
        <div class="row grand"><span>TOTAL</span><span>₹{{ number_format($order->total,2) }}</span></div>
    </div>

    <div class="footer">
        <p>Thank you for ordering with BYTE2BITE! 🍔</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <button onclick="window.print()" style="margin-top:15px;background:#e42e0c;color:#fff;border:none;padding:8px 25px;border-radius:5px;cursor:pointer;font-family:'Poppins',sans-serif;">Print Invoice</button>
    </div>
</div>
</body>
</html>
