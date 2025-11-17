<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk Pesanan - {{ $order->order_code }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #222; }
        .receipt { max-width: 480px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 10px; }
        .items { width: 100%; margin-top: 10px; }
        .items td { padding: 6px 0; }
        .total { font-weight: 700; font-size: 1.1rem; }
        .muted { color: #666; font-size: 0.9rem; }
        .print-btn { display:block; margin: 12px auto; padding: 10px 18px; background:#6a1b9a; color:white; text-decoration:none; border-radius:6px; text-align:center; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>Atun Laundry</h2>
            <div class="muted">Struk Pesanan</div>
            <div class="muted">{{ $order->order_code }}</div>
        </div>

        <table>
            <tr><td>Nama</td><td>: {{ $order->customer_name }}</td></tr>
            <tr><td>Telepon</td><td>: {{ $order->customer_phone }}</td></tr>
            <tr><td>Alamat</td><td>: {{ $order->customer_address }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $order->created_at->format('d M Y H:i') }}</td></tr>
        </table>

        <hr>

        <table class="items">
            <tbody>
                @if($order->items && $order->items->count())
                    @foreach($order->items as $item)
                        <tr><td>{{ $item->name }} ({{ $item->quantity }} {{ $item->unit ?? '' }})</td><td style="text-align:right">Rp {{ number_format($item->price,0,',','.') }}</td></tr>
                    @endforeach
                @else
                    <tr><td>{{ $order->service->name }} {{ $order->weight ? '('.$order->weight.' KG)' : '' }}</td><td style="text-align:right">Rp {{ number_format($order->price ?? 0,0,',','.') }}</td></tr>
                @endif
            </tbody>
        </table>

        <hr>
        <div style="display:flex;justify-content:space-between;">
            <div class="muted">Status</div>
            <div class="fw-semibold">{{ $order->status_display }}</div>
        </div>
        <div style="display:flex;justify-content:space-between; margin-top:8px;" class="total">
            <div>Total</div>
            <div>Rp {{ number_format($order->price ?? 0,0,',','.') }}</div>
        </div>

        <a href="#" class="print-btn" onclick="window.print();return false;">Cetak / Download</a>
    </div>
</body>
</html>