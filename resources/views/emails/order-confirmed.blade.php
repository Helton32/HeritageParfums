@extends('emails.layout')

@section('title', 'Confirmation de votre commande')

@section('content')
    <h2 style="color: #212529; margin-bottom: 20px;">Commande confirmée !</h2>
    
    <p>Bonjour {{ $order->customer_name }},</p>
    
    <p>Nous avons bien reçu votre paiement et votre commande est confirmée. Voici le récapitulatif de votre achat :</p>
    
    <div class="order-info">
        <h3 style="margin: 0 0 10px 0; color: #212529;">Informations de commande</h3>
        <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
        <p><strong>Statut :</strong> {{ $order->status_label }}</p>
        <p><strong>Statut du paiement :</strong> {{ $order->payment_status_label }}</p>
    </div>
    
    <div class="order-items">
        <h3 style="color: #212529;">Articles commandés</h3>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        @if($item->product_size)
                            <small>Taille: {{ $item->product_size }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->product_price, 2) }} €</td>
                    <td>{{ number_format($item->total_price, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="order-total">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: right; padding: 5px 0;"><strong>Sous-total :</strong></td>
                <td style="border: none; text-align: right; padding: 5px 0;">{{ number_format($order->subtotal, 2) }} €</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; padding: 5px 0;"><strong>TVA (20%) :</strong></td>
                <td style="border: none; text-align: right; padding: 5px 0;">{{ number_format($order->tax_amount, 2) }} €</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; padding: 5px 0;"><strong>Frais de livraison :</strong></td>
                <td style="border: none; text-align: right; padding: 5px 0;">
                    @if($order->shipping_amount == 0)
                        <span style="color: #28a745;">Gratuite</span>
                    @else
                        {{ number_format($order->shipping_amount, 2) }} €
                    @endif
                </td>
            </tr>
            <tr style="border-top: 2px solid #212529;">
                <td style="border: none; text-align: right; padding: 10px 0 5px 0; font-size: 18px;"><strong>TOTAL :</strong></td>
                <td style="border: none; text-align: right; padding: 10px 0 5px 0; font-size: 18px; font-weight: bold;">{{ number_format($order->total_amount, 2) }} €</td>
            </tr>
        </table>
    </div>
    
    @if($order->shipping_address_line_1)
    <div class="address-section">
        <h3 style="color: #212529; margin: 0 0 15px 0;">Adresse de livraison</h3>
        <p style="margin: 0;">
            {{ $order->customer_name }}<br>
            {{ $order->shipping_address_line_1 }}<br>
            @if($order->shipping_address_line_2)
                {{ $order->shipping_address_line_2 }}<br>
            @endif
            {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
            {{ $order->shipping_country }}
        </p>
    </div>
    @endif
    
    <div style="margin: 30px 0;">
        <h3 style="color: #212529;">Prochaines étapes</h3>
        <p>Votre commande va être préparée avec soin par notre équipe. Vous recevrez un nouvel email avec les informations de suivi dès que votre colis sera expédié.</p>
        
        @if($order->shipping_amount == 0)
            <p><strong>🎉 Livraison gratuite !</strong> Votre commande de {{ number_format($order->subtotal, 2) }} € bénéficie de la livraison gratuite.</p>
        @endif
        
        <p>Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter en précisant votre numéro de commande.</p>
    </div>
@endsection
