<!DOCTYPE html>
<html>
<head>
    <title>Test Apple Pay - Héritaj Parfums</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, sans-serif; padding: 20px; }
        .status { padding: 10px; margin: 10px 0; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>
    <h1>🍎 Test Apple Pay - Héritaj Parfums</h1>
    
    <div class="status info">
        <strong>URL actuelle:</strong> {{ url('/') }}
    </div>
    
    <div class="status info">
        <strong>Environment:</strong> {{ app()->environment() }}
    </div>
    
    <div class="status info">
        <strong>HTTPS:</strong> {{ request()->secure() ? '✅ Activé' : '❌ Désactivé' }}
    </div>
    
    <div class="status info">
        <strong>Apple Pay Merchant ID:</strong> {{ config('apple-pay.merchant_identifier') }}
    </div>
    
    <div class="status info">
        <strong>Apple Pay Domain:</strong> {{ config('apple-pay.domain_name') }}
    </div>
    
    <div class="status {{ request()->secure() && app()->environment('production') ? 'success' : 'error' }}">
        {{ request()->secure() && app()->environment('production') ? '✅ Configuration OK pour Apple Pay' : '❌ Configuration non optimale pour Apple Pay' }}
    </div>
    
    <hr>
    
    <h2>🧪 Test JavaScript Apple Pay</h2>
    <div id="apple-pay-status" class="status info">Vérification en cours...</div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusDiv = document.getElementById('apple-pay-status');
            
            if (window.ApplePaySession) {
                if (ApplePaySession.canMakePayments()) {
                    statusDiv.className = 'status success';
                    statusDiv.innerHTML = '✅ Apple Pay est disponible sur cet appareil !';
                    
                    // Test avec votre merchant ID
                    ApplePaySession.canMakePaymentsWithActiveCard('{{ config("apple-pay.merchant_identifier") }}')
                        .then(function(canMakePayments) {
                            if (canMakePayments) {
                                statusDiv.innerHTML = '✅ Apple Pay est complètement configuré et prêt !';
                            } else {
                                statusDiv.className = 'status error';
                                statusDiv.innerHTML = '⚠️ Apple Pay disponible mais aucune carte configurée';
                            }
                        })
                        .catch(function(error) {
                            statusDiv.className = 'status error';
                            statusDiv.innerHTML = '❌ Erreur lors de la vérification des cartes: ' + error.message;
                        });
                } else {
                    statusDiv.className = 'status error';
                    statusDiv.innerHTML = '❌ Apple Pay non supporté sur cet appareil';
                }
            } else {
                statusDiv.className = 'status error';
                statusDiv.innerHTML = '❌ ApplePaySession non disponible (navigateur non compatible)';
            }
        });
    </script>
    
    <hr>
    
    <p><a href="/product/eternelle-rose">🌹 Aller à la page produit pour tester</a></p>
</body>
</html>