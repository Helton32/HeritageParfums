@extends('emails.layout')

@section('title', 'Votre commande a été expédiée')

@section('content')
    <h2 style="color: #212529; margin-bottom: 20px;">Votre commande a été expédiée ! 📦</h2>
    
    <p>Bonjour {{ $order->customer_name }},</p>
    
    <p>Excellente nouvelle ! Votre commande a été expédiée et devrait arriver bientôt à votre adresse de livraison.</p>
    
    <div class="order-info">
        <h3 style="margin: 0 0 10px 0; color: #212529;">Informations d'expédition</h3>
        <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
        <p><strong>Date d'expédition :</strong> {{ $order->shipped_at->format('d/m/Y à H:i') }}</p>
        <p><strong>Transporteur :</strong> {{ $order->carrier_name ?? 'Standard' }}</p>
        @if($order->tracking_number)
            <p><strong>Numéro de suivi :</strong> 
                <code style="background-color: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $order->tracking_number }}</code>
            </p>
        @endif
    </div>
    
    @if($order->tracking_number)
    <div style="text-align: center; margin: 30px 0;">
        <a href="#" class="btn">Suivre ma commande</a>
        <p style="font-size: 14px; color: #6c757d; margin-top: 10px;">
            Le suivi peut prendre quelques heures pour être actif
        </p>
    </div>
    @endif
    
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
    
    <div class="order-items">
        <h3 style="color: #212529;">Rappel de votre commande</h3>
        <table>
            <thead>
                <tr>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin: 30px 0; padding: 20px; background-color: #e8f4f8; border-radius: 5px;">
        <h3 style="color: #212529; margin: 0 0 15px 0;">📍 Conseils de réception</h3>
        <ul style="margin: 0; padding-left: 20px;">
            <li>Assurez-vous qu'une personne soit présente à l'adresse de livraison</li>
            <li>Vérifiez votre boîte aux lettres pour d'éventuels avis de passage</li>
            <li>Les parfums doivent être conservés à l'abri de la lumière et de la chaleur</li>
            @if($order->tracking_number)
                <li>Vous pouvez suivre votre colis en temps réel avec le numéro de suivi</li>
            @endif
        </ul>
    </div>
    
    <div style="margin: 30px 0;">
        <h3 style="color: #212529;">Des questions ?</h3>
        <p>Si vous avez des questions concernant votre livraison, n'hésitez pas à nous contacter en précisant votre numéro de commande <strong>{{ $order->order_number }}</strong>.</p>
        
        <p>Nous espérons que vous apprécierez vos nouveaux parfums Heritage Parfums ! ✨</p>
    </div>
@endsection
