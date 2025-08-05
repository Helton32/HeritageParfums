<?php

namespace App\Http\Controllers;

use App\Models\ShippingCarrier;
use Illuminate\Http\Request;

class ShippingApiController extends Controller
{
    /**
     * Get available shipping options for a given location
     */
    public function getShippingOptions(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|string|min:3',
            'country' => 'required|string|size:2',
            'weight' => 'required|numeric|min:0.01',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $postalCode = $request->postal_code;
        $country = strtolower($request->country);
        $weight = $request->weight;
        $subtotal = $request->subtotal;

        // Determine shipping zone
        $zone = $this->determineShippingZone($country);

        // Get available carriers for this zone
        $carriers = ShippingCarrier::active()
            ->get()
            ->filter(function ($carrier) use ($zone) {
                return $carrier->servesZone($zone);
            });

        $availableOptions = [];

        foreach ($carriers as $carrier) {
            $availableMethods = $carrier->getAvailableMethodsForZone($zone);
            
            if (empty($availableMethods)) {
                continue;
            }

            $carrierData = [
                'code' => $carrier->code,
                'name' => $carrier->name,
                'logo_path' => $carrier->logo_path,
                'methods' => []
            ];

            foreach ($availableMethods as $methodCode => $methodData) {
                $price = $carrier->calculateShippingPrice($weight, $zone, $methodCode);
                
                // Apply free shipping rules
                if ($subtotal >= 150 && !in_array('express', $methodData['features'] ?? [])) {
                    $price = 0;
                }

                $carrierData['methods'][$methodCode] = $methodData;
                $carrierData['pricing'][$methodCode] = $price;
            }

            $availableOptions[] = $carrierData;
        }

        return response()->json([
            'success' => true,
            'carriers' => $availableOptions,
            'zone' => $zone
        ]);
    }

    /**
     * Search for relay points
     */
    public function searchRelayPoints(Request $request)
    {
        $request->validate([
            'carrier' => 'required|string',
            'postal_code' => 'required|string',
            'city' => 'nullable|string'
        ]);

        $carrier = $request->carrier;
        $postalCode = $request->postal_code;
        $city = $request->city;

        // Mock data for demonstration
        // In production, you would call the actual carrier APIs
        $points = $this->getMockRelayPoints($carrier, $postalCode, $city);

        return response()->json([
            'success' => true,
            'points' => $points
        ]);
    }
    /**
     * Determine shipping zone based on country
     */
    private function determineShippingZone($country)
    {
        $country = strtolower($country);
        
        if ($country === 'fr') {
            return 'france';
        } elseif (in_array($country, ['be', 'lu', 'mc', 'ad'])) {
            return 'europe_proche';
        } elseif (in_array($country, ['de', 'es', 'it', 'nl', 'pt', 'ch', 'at'])) {
            return 'europe';
        } else {
            return 'international';
        }
    }

    /**
     * Get mock relay points for demonstration
     * In production, replace with actual API calls
     */
    private function getMockRelayPoints($carrier, $postalCode, $city = null)
    {
        // Mock data based on carrier
        $points = [];

        if ($carrier === 'mondialrelay') {
            $points = [
                [
                    'id' => 'MR001',
                    'name' => 'Mondial Relay - Tabac Presse',
                    'address' => '123 Rue de la République',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 0.5,
                    'opening_hours' => 'Lun-Sam: 8h-19h'
                ],
                [
                    'id' => 'MR002',
                    'name' => 'Mondial Relay - Supermarché',
                    'address' => '45 Avenue des Champs',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 1.2,
                    'opening_hours' => 'Lun-Dim: 8h-20h'
                ],
                [
                    'id' => 'MR003',
                    'name' => 'Mondial Relay - Pharmacie',
                    'address' => '67 Place du Marché',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 2.1,
                    'opening_hours' => 'Lun-Ven: 9h-19h, Sam: 9h-12h'
                ]
            ];
        } elseif ($carrier === 'colissimo') {
            $points = [
                [
                    'id' => 'COL001',
                    'name' => 'Bureau de Poste Principal',
                    'address' => '1 Place de la Poste',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 0.8,
                    'opening_hours' => 'Lun-Ven: 8h30-18h, Sam: 8h30-12h'
                ],
                [
                    'id' => 'COL002',
                    'name' => 'Bureau de Poste Annexe',
                    'address' => '89 Rue Jean Jaurès',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 1.5,
                    'opening_hours' => 'Lun-Ven: 9h-17h'
                ]
            ];
        } elseif ($carrier === 'chronopost') {
            $points = [
                [
                    'id' => 'CHR001',
                    'name' => 'Chronopost Relais - Station Service',
                    'address' => '12 Boulevard National',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 1.0,
                    'opening_hours' => 'Lun-Dim: 6h-22h'
                ],
                [
                    'id' => 'CHR002',
                    'name' => 'Chronopost Relais - Centre Commercial',
                    'address' => 'Centre Commercial Les Arcades',
                    'postal_code' => $postalCode,
                    'city' => $city ?: 'Ville',
                    'distance' => 2.3,
                    'opening_hours' => 'Lun-Sam: 10h-20h'
                ]
            ];
        }

        // Sort by distance
        usort($points, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return $points;
    }

    /**
     * Calculate shipping price with rules
     */
    public function calculateShippingPrice(Request $request)
    {
        $request->validate([
            'carrier' => 'required|string',
            'method' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string|size:2',
            'weight' => 'required|numeric|min:0.01',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $carrier = ShippingCarrier::where('code', $request->carrier)->active()->first();
        
        if (!$carrier) {
            return response()->json([
                'success' => false,
                'message' => 'Transporteur non trouvé'
            ], 404);
        }

        $zone = $this->determineShippingZone($request->country);
        $price = $carrier->calculateShippingPrice($request->weight, $zone, $request->method);

        // Apply free shipping rules
        if ($request->subtotal >= 150) {
            $methodData = $carrier->methods[$request->method] ?? [];
            $isExpress = in_array('express', $methodData['features'] ?? []);
            
            if (!$isExpress) {
                $price = 0;
            }
        }

        return response()->json([
            'success' => true,
            'price' => $price,
            'currency' => 'EUR',
            'free_shipping_eligible' => $request->subtotal >= 150
        ]);
    }
}