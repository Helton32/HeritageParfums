<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ShippingCarrier;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * Liste des commandes à expédier
     */
    public function index()
    {
        $orders = Order::where('payment_status', 'paid')
                      ->where('status', 'processing')
                      ->with(['items', 'shippingCarrier'])
                      ->latest()
                      ->paginate(20);

        return view('admin.shipping.index', compact('orders'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order)
    {
        try {
            $order->load(['items', 'shippingCarrier']);
            $availableCarriers = $this->shippingService->getAvailableCarriers($order);

            return view('admin.shipping.show', compact('order', 'availableCarriers'));
        } catch (\Exception $e) {
            \Log::error('Erreur shipping show: ' . $e->getMessage());
            return redirect()->route('admin.shipping.index')
                            ->with('error', 'Erreur lors du chargement de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Assigner un transporteur à une commande
     */
    public function assignCarrier(Request $request, Order $order)
    {
        $request->validate([
            'shipping_carrier' => 'required|exists:shipping_carriers,code',
            'shipping_method' => 'required|string',
            'carrier_options' => 'nullable|array'
        ]);

        $order->update([
            'shipping_carrier' => $request->shipping_carrier,
            'shipping_method' => $request->shipping_method,
            'carrier_options' => $request->carrier_options ?? [],
            'shipping_weight' => $order->calculatePackageWeight(),
            'package_dimensions' => $order->getPackageDimensions(),
            'carrier_reference' => $order->generateCarrierReference()
        ]);

        return redirect()->route('admin.shipping.show', $order)
                        ->with('success', 'Transporteur assigné avec succès');
    }

    /**
     * Générer le bon de livraison
     */
    public function generateLabel(Order $order)
    {
        try {
            if (!$order->shipping_carrier) {
                return back()->with('error', 'Aucun transporteur assigné à cette commande');
            }

            $labelPath = $this->shippingService->createShippingLabel($order);
            $trackingNumber = $this->shippingService->generateTrackingNumber($order);

            return redirect()->route('admin.shipping.show', $order)
                            ->with('success', "Bon de livraison généré avec succès. N° de suivi: {$trackingNumber}");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du bon: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger le bon de livraison PDF
     */
    public function downloadLabel(Order $order)
    {
        if (!$order->shipping_label_path || !Storage::disk('public')->exists($order->shipping_label_path)) {
            return back()->with('error', 'Bon de livraison non trouvé');
        }

        $filename = "bon-livraison-{$order->order_number}.pdf";
        
        return Storage::disk('public')->download($order->shipping_label_path, $filename);
    }

    /**
     * Marquer comme expédié
     */
    public function markAsShipped(Order $order)
    {
        if (!$order->shipping_label_path) {
            return back()->with('error', 'Veuillez générer le bon de livraison avant de marquer comme expédié');
        }

        $order->markAsShipped();

        return redirect()->route('admin.shipping.index')
                        ->with('success', 'Commande marquée comme expédiée');
    }

    /**
     * API: Obtenir les méthodes disponibles pour un transporteur
     */
    public function getCarrierMethods(Request $request)
    {
        $carrierCode = $request->get('carrier');
        $weight = $request->get('weight', 0.5);
        $zone = $request->get('zone', 'france');

        $carrier = ShippingCarrier::where('code', $carrierCode)->first();
        
        if (!$carrier) {
            return response()->json(['error' => 'Transporteur non trouvé'], 404);
        }

        $methods = $carrier->getAvailableMethodsForZone($zone);
        $methodsWithPricing = [];

        foreach ($methods as $methodCode => $methodData) {
            $price = $carrier->calculateShippingPrice($weight, $zone, $methodCode);
            $methodsWithPricing[$methodCode] = [
                'name' => $methodData['name'],
                'description' => $methodData['description'],
                'delivery_time' => $methodData['delivery_time'],
                'price' => $price
            ];
        }

        return response()->json($methodsWithPricing);
    }

    /**
     * API: Rechercher des points relais (simulation pour Mondial Relay)
     */
    public function searchRelayPoints(Request $request)
    {
        $postalCode = $request->get('postal_code');
        $city = $request->get('city');

        // Simulation de points relais pour la démo
        $relayPoints = [
            [
                'code' => 'MR001',
                'name' => 'Tabac de la Gare',
                'address' => '45 Avenue de la Gare',
                'postal_code' => $postalCode,
                'city' => $city,
                'phone' => '01 23 45 67 89',
                'hours' => 'Lun-Sam: 7h-19h',
                'distance' => '0.2 km'
            ],
            [
                'code' => 'MR002',
                'name' => 'Supermarché U Express',
                'address' => '123 Rue du Commerce',
                'postal_code' => $postalCode,
                'city' => $city,
                'phone' => '01 23 45 67 90',
                'hours' => 'Lun-Dim: 8h-20h',
                'distance' => '0.5 km'
            ],
            [
                'code' => 'MR003',
                'name' => 'Pressing Eclair',
                'address' => '67 Boulevard Saint-Michel',
                'postal_code' => $postalCode,
                'city' => $city,
                'phone' => '01 23 45 67 91',
                'hours' => 'Lun-Ven: 9h-18h, Sam: 9h-16h',
                'distance' => '0.8 km'
            ]
        ];

        return response()->json($relayPoints);
    }

    /**
     * Statistiques des expéditions
     */
    public function statistics()
    {
        $stats = [
            'pending_shipments' => Order::where('payment_status', 'paid')
                                       ->where('status', 'processing')
                                       ->count(),
            'shipped_today' => Order::where('status', 'shipped')
                                   ->whereDate('shipped_at', today())
                                   ->count(),
            'shipped_this_week' => Order::where('status', 'shipped')
                                        ->whereBetween('shipped_at', [now()->startOfWeek(), now()->endOfWeek()])
                                        ->count(),
            'by_carrier' => Order::selectRaw('shipping_carrier, COUNT(*) as count')
                                 ->where('status', 'shipped')
                                 ->groupBy('shipping_carrier')
                                 ->pluck('count', 'shipping_carrier')
                                 ->toArray()
        ];

        return view('admin.shipping.statistics', compact('stats'));
    }
}
