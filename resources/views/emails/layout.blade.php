<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Heritage Parfums</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
        
        .email-header {
            background-color: #212529;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
            letter-spacing: 2px;
        }
        
        .email-content {
            padding: 30px 20px;
        }
        
        .order-info {
            background-color: #f8f9fa;
            border-left: 4px solid #212529;
            padding: 15px 20px;
            margin: 20px 0;
        }
        
        .order-items {
            margin: 20px 0;
        }
        
        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .order-items th,
        .order-items td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        
        .order-items th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .order-total {
            margin: 20px 0;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        
        .btn {
            display: inline-block;
            background-color: #212529;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        
        .address-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>HERITAGE PARFUMS</h1>
        </div>
        
        <div class="email-content">
            @yield('content')
        </div>
        
        <div class="email-footer">
            <p>Merci de votre confiance,<br>
            L'équipe Heritage Parfums</p>
            
            <p>
                <strong>Heritage Parfums</strong><br>
                Email: contact@heritage-parfums.fr<br>
                Web: www.heritage-parfums.fr
            </p>
            
            <p style="font-size: 12px; color: #adb5bd;">
                Cet email a été envoyé automatiquement, merci de ne pas y répondre directement.
            </p>
        </div>
    </div>
</body>
</html>
