<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de livraison Chronopost - {{ $order->order_number }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            font-size: 12px;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .logo { 
            font-size: 24px; 
            font-weight: bold; 
            color: #1e40af;
        }
        .carrier-logo {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
        }
        .section { 
            margin-bottom: 20px; 
            border: 1px solid #ccc;
            padding: 15px;
        }
        .section-title { 
            font-weight: bold; 
            font-size: 14px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .flex { 
            display: flex; 
            justify-content: space-between; 
        }
        .address-box { 
            width: 45%; 
            border: 2px solid #dc2626;
            padding: 10px;
            min-height: 100px;
        }
        .barcode { 
            text-align: center; 
            font-family: "Courier New", monospace;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #dc2626;
            background-color: #fef2f2;
        }
        .tracking-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px;
            background-color: #dc2626;
            color: white;
        }
        .package-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
        }
        .service-info {
            background-color: #fef2f2;
            border: 1px solid #dc2626;
            padding: 10px;
            margin: 10px 0;
            font-weight: bold;
            color: #dc2626;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 10px 0;
        }
        th, td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #fef2f2; 
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .urgent {
            background-color: #dc2626;
            color: white;
            padding: 5px 10px;
            display: inline-block;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Héritage Parfums</div>
        <div class="carrier-logo">CHRONOPOST</div>
    </div>

    <div class="service-info">
        Code Service: {{ $service_code }} - 
        @if($order->shipping_method === 'express')
            <span class="urgent">CHRONOPOST EXPRESS (13h)</span>
        @elseif($order->shipping_method === 'saturday')
            <span class="urgent">LIVRAISON SAMEDI</span>
        @else
            Chronopost Standard (24h)
        @endif
    </div>

    @if($order->tracking_number)
    <div class="tracking-number">
        N° de suivi Chronopost: {{ $order->tracking_number }}
    </div>
    @endif

    <div class="barcode">
        {{ $barcode }}
    </div>

    <div class="flex">
        <div class="address-box">
            <div class="section-title">EXPÉDITEUR</div>
            <strong>Héritage Parfums</strong><br>
            123 Avenue des Champs-Élysées<br>
            75008 Paris<br>
            France<br>
            Tél: +33 1 23 45 67 89<br>
            Email: contact@heritage-parfums.fr<br>
            <strong>N° Client Chronopost: 12345678</strong>
        </div>

        <div class="address-box">
            <div class="section-title">DESTINATAIRE</div>
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->shipping_address_line_1 }}<br>
            @if($order->shipping_address_line_2)
                {{ $order->shipping_address_line_2 }}<br>
            @endif
            {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
            {{ strtoupper($order->shipping_country) }}<br>
            @if($order->customer_phone)
                <strong>Tél: {{ $order->customer_phone }}</strong><br>
            @endif
            Email: {{ $order->customer_email }}
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMATIONS COMMANDE</div>
        <strong>N° Commande:</strong> {{ $order->order_number }}<br>
        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
        <strong>Référence transporteur:</strong> {{ $order->carrier_reference }}<br>
        <strong>Livraison prévue:</strong> 
        @if($order->shipping_method === 'express')
            {{ $order->created_at->addDay()->format('d/m/Y avant 13h') }}
        @else
            {{ $order->created_at->addDay()->format('d/m/Y') }}
        @endif
    </div>

    <div class="section">
        <div class="section-title">CONTENU DU COLIS</div>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Taille</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->product_type }}</td>
                    <td>{{ $item->product_size }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->product_price, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="package-info">
        <strong>Informations colis:</strong><br>
        Poids: {{ number_format($order->shipping_weight, 3) }} kg<br>
        Dimensions: {{ $order->package_dimensions['length'] }}x{{ $order->package_dimensions['width'] }}x{{ $order->package_dimensions['height'] }} cm<br>
        Valeur déclarée: {{ number_format($order->total_amount, 2) }} €<br>
        <strong>Service: Livraison avec signature</strong>
    </div>

    @if($order->shipping_method === 'express')
    <div class="urgent">
        ⚠️ COLIS URGENT - LIVRAISON AVANT 13H ⚠️
    </div>
    @endif

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Héritage Parfums<br>
        En partenariat avec Chronopost - La Poste Groupe
    </div>
</body>
</html>
