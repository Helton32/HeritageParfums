<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bon de livraison Mondial Relay - {{ $order->order_number }}</title>
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
            color: #16a34a;
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
            border: 2px solid #16a34a;
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
            border: 2px solid #16a34a;
            background-color: #f0fdf4;
        }
        .tracking-number {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px;
            background-color: #16a34a;
            color: white;
        }
        .package-info {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
        }
        .service-info {
            background-color: #f0fdf4;
            border: 1px solid #16a34a;
            padding: 10px;
            margin: 10px 0;
            font-weight: bold;
            color: #16a34a;
        }
        .relay-point {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px;
            margin: 15px 0;
            font-weight: bold;
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
            background-color: #f0fdf4; 
            font-weight: bold;
            color: #16a34a;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .important {
            background-color: #f59e0b;
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
        <div class="carrier-logo">MONDIAL RELAY</div>
    </div>

    <div class="service-info">
        Service: Livraison en Point Relais® - 
        Délai: 2-4 jours ouvrés
    </div>

    @if($point_relais)
    <div class="relay-point">
        <div class="section-title">📍 POINT RELAIS DE LIVRAISON</div>
        <strong>{{ $point_relais['name'] ?? 'Point Relais' }}</strong><br>
        {{ $point_relais['address'] ?? $order->shipping_address_line_1 }}<br>
        {{ $point_relais['postal_code'] ?? $order->shipping_postal_code }} {{ $point_relais['city'] ?? $order->shipping_city }}<br>
        @if(isset($point_relais['phone']))
            Tél: {{ $point_relais['phone'] }}<br>
        @endif
        @if(isset($point_relais['hours']))
            Horaires: {{ $point_relais['hours'] }}<br>
        @endif
        <strong>Code Point Relais: {{ $point_relais['code'] ?? 'MR001' }}</strong>
    </div>
    @endif

    @if($order->tracking_number)
    <div class="tracking-number">
        N° de suivi Mondial Relay: {{ $order->tracking_number }}
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
            <strong>N° Client MR: 87654321</strong>
        </div>

        <div class="address-box">
            <div class="section-title">DESTINATAIRE</div>
            <strong>{{ $order->customer_name }}</strong><br>
            @if($order->customer_phone)
                <strong>Tél: {{ $order->customer_phone }}</strong><br>
            @endif
            Email: {{ $order->customer_email }}<br><br>
            <em>Colis à retirer au Point Relais ci-dessus</em><br>
            <strong>Pièce d'identité obligatoire</strong>
        </div>
    </div>

    <div class="section">
        <div class="section-title">INFORMATIONS COMMANDE</div>
        <strong>N° Commande:</strong> {{ $order->order_number }}<br>
        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
        <strong>Référence transporteur:</strong> {{ $order->carrier_reference }}<br>
        <strong>Disponible à partir du:</strong> {{ $order->created_at->addDays(2)->format('d/m/Y') }}<br>
        <strong>Durée de conservation:</strong> 14 jours
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
        Valeur déclarée: {{ number_format($order->total_amount, 2) }} €
    </div>

    <div class="important">
        ⚠️ INSTRUCTIONS IMPORTANTES ⚠️
    </div>
    <div style="background-color: #fef3c7; padding: 10px; margin: 10px 0;">
        • Le destinataire sera prévenu par SMS/email de l'arrivée du colis<br>
        • Présenter une pièce d'identité pour retirer le colis<br>
        • Colis conservé 14 jours maximum au Point Relais<br>
        • Suivi disponible sur www.mondialrelay.fr
    </div>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} - Héritage Parfums<br>
        En partenariat avec Mondial Relay
    </div>
</body>
</html>
