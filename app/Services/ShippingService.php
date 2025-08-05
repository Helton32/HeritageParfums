<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShippingCarrier;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ShippingService
{
    /**
     * Créer un bon de livraison pour une commande
     */
    public function createShippingLabel(Order $order)
    {
        if (!$order->shipping_carrier) {
            throw new \Exception('Aucun transporteur sélectionné pour cette commande');
        }

        $carrier = ShippingCarrier::where('code', $order->shipping_carrier)->first();
        
        if (!$carrier) {
            throw new \Exception('Transporteur non trouvé');
        }

        // Calculer le poids et les dimensions si pas déjà fait
        if (!$order->shipping_weight) {
            $order->shipping_weight = $order->calculatePackageWeight();
        }
        
        if (!$order->package_dimensions) {
            $order->package_dimensions = $order->getPackageDimensions();
        }

        // Générer la référence transporteur
        if (!$order->carrier_reference) {
            $order->carrier_reference = $order->generateCarrierReference();
        }

        $order->save();

        // Créer le bon selon le transporteur
        switch ($order->shipping_carrier) {
            case 'colissimo':
                return $this->createColissimoLabel($order);
            case 'chronopost':
                return $this->createChronopostLabel($order);
            case 'mondialrelay':
                return $this->createMondialRelayLabel($order);
            default:
                throw new \Exception('Transporteur non supporté');
        }
    }

    /**
     * Créer un bon Colissimo
     */
    private function createColissimoLabel(Order $order)
    {
        $data = [
            'order' => $order,
            'carrier' => 'Colissimo',
            'service_code' => $this->getColissimoServiceCode($order->shipping_method),
            'barcode' => $this->generateBarcode($order),
        ];

        return $this->generatePdfLabel($order, $data, 'colissimo');
    }

    /**
     * Créer un bon Chronopost
     */
    private function createChronopostLabel(Order $order)
    {
        $data = [
            'order' => $order,
            'carrier' => 'Chronopost',
            'service_code' => $this->getChronopostServiceCode($order->shipping_method),
            'barcode' => $this->generateBarcode($order),
        ];

        return $this->generatePdfLabel($order, $data, 'chronopost');
    }

    /**
     * Créer un bon Mondial Relay
     */
    private function createMondialRelayLabel(Order $order)
    {
        $data = [
            'order' => $order,
            'carrier' => 'Mondial Relay',
            'point_relais' => $order->carrier_options['point_relais'] ?? null,
            'barcode' => $this->generateBarcode($order),
        ];

        return $this->generatePdfLabel($order, $data, 'mondialrelay');
    }

    /**
     * Générer le PDF du bon de livraison
     */
    private function generatePdfLabel(Order $order, array $data, string $template)
    {
        $pdf = Pdf::loadView("shipping.labels.{$template}", $data);
        $pdf->setPaper('A4');
        
        $filename = "bon-livraison-{$order->order_number}.pdf";
        $path = "shipping-labels/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        // Sauvegarder le chemin dans la commande
        $order->shipping_label_path = $path;
        $order->save();
        
        return $path;
    }

    /**
     * Obtenir les transporteurs disponibles pour une commande
     */
    public function getAvailableCarriers(Order $order)
    {
        $zone = $order->getShippingZone();
        $weight = $order->calculatePackageWeight();
        
        $carriers = ShippingCarrier::active()->get();
        $available = [];
        
        foreach ($carriers as $carrier) {
            if ($carrier->servesZone($zone)) {
                $methods = $carrier->getAvailableMethodsForZone($zone);
                $carrierData = [
                    'carrier' => $carrier,
                    'methods' => []
                ];
                
                foreach ($methods as $methodCode => $methodData) {
                    $price = $carrier->calculateShippingPrice($weight, $zone, $methodCode);
                    $carrierData['methods'][$methodCode] = [
                        'name' => $methodData['name'],
                        'description' => $methodData['description'],
                        'price' => $price,
                        'delivery_time' => $methodData['delivery_time']
                    ];
                }
                
                $available[] = $carrierData;
            }
        }
        
        return $available;
    }

    /**
     * Codes de service Colissimo
     */
    private function getColissimoServiceCode($method)
    {
        return match($method) {
            'standard' => 'DOM',
            'express' => 'DOMR',
            'pickup' => 'BPR',
            default => 'DOM'
        };
    }

    /**
     * Codes de service Chronopost
     */
    private function getChronopostServiceCode($method)
    {
        return match($method) {
            'standard' => '01',
            'express' => '02',
            'saturday' => '06',
            default => '01'
        };
    }

    /**
     * Générer un code-barres factice pour la démo
     */
    private function generateBarcode(Order $order)
    {
        return '3S' . str_pad($order->id, 8, '0', STR_PAD_LEFT) . 'FR';
    }

    /**
     * Simuler l'appel API pour obtenir un numéro de suivi
     */
    public function generateTrackingNumber(Order $order)
    {
        switch ($order->shipping_carrier) {
            case 'colissimo':
                $tracking = 'CN' . rand(100000000, 999999999) . 'FR';
                break;
            case 'chronopost':
                $tracking = 'CX' . rand(100000000, 999999999) . 'FR';
                break;
            case 'mondialrelay':
                $tracking = 'MR' . rand(10000000, 99999999);
                break;
            default:
                $tracking = 'HP' . rand(100000000, 999999999);
        }

        $order->tracking_number = $tracking;
        $order->save();

        return $tracking;
    }
}
