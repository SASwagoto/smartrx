<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDataTable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('backend.products.index');
    }

    /**
     * Comment: Fetch products from CodeIgniter API and sync with local database
     */
    public function syncProducts()
    {
        // Comment: Retrieve API URL and Secret Token from environment variables
        $apiUrl = env('CI_API_URL');
        $secretToken = env('CI_API_TOKEN');

        // Comment: Validate if env variables exist
        if (! $apiUrl || ! $secretToken) {
            return response()->json([
                'status' => false,
                'message' => 'API URL or Token is missing in environment configuration.',
            ], 500);
        }

        try {
            // Comment: Send HTTP GET request with Bearer Token
            $response = Http::withToken($secretToken)->get($apiUrl);

            // Comment: Check if the request was successful
            if ($response->successful()) {
                $result = $response->json();

                // Comment: Verify status and data existence from API response
                if (isset($result['status']) && $result['status'] === true && isset($result['data'])) {

                    $counter = 0;

                    foreach ($result['data'] as $item) {
                        // Comment: Update existing product or create new one based on source_id (CI ID)
                        Product::updateOrCreate(
                            ['source_id' => $item['id']], // Unique identifier tracking
                            [
                                'code' => $item['code'] ?? null,
                                'name' => $item['name'],
                                'generic_name' => $item['genname'] ?? null,
                                'price' => $item['price'] ?? 0,
                                'quantity' => $item['quantity'] ?? 0,
                                'brand' => $item['brand'] ?? null,
                                'category_id' => $item['category_id'] ?? null,
                                'category_code' => $item['category_code'] ?? null,
                                'category_name' => $item['category_name'] ?? null,
                            ]
                        );
                        $counter++;
                    }

                    return response()->json([
                        'status' => true,
                        'message' => "Successfully synchronized {$counter} products.",
                    ], 200);
                }
            }

            // Comment: Handle API response failure status
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch data from remote server or invalid response format.',
            ], 500);

        } catch (\Exception $e) {
            // Comment: Handle connection or unexpected errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during synchronization: '.$e->getMessage(),
            ], 500);
        }
    }

    public function searchMedicines(Request $request)
    {
        $search = $request->get('q');

        // Comment: Cache products as a plain array to avoid PHP_Incomplete_Class serialization issue
        $products = Cache::remember('all_medicines_list', 3600, function () {
            // Comment: Fetch id, category_code, name, generic_name, and quantity, sorted by highest quantity first
            return Product::select('id', 'category_code', 'name', 'generic_name', 'quantity')
                ->orderByDesc('quantity')
                ->get()
                ->toArray();
        });

        // Comment: Filter products from array based on name, generic_name or category_code keyword
        if ($search) {
            $searchLower = strtolower($search);
            $products = array_filter($products, function ($item) use ($searchLower) {
                return stripos($item['name'], $searchLower) !== false ||
                       stripos($item['generic_name'], $searchLower) !== false ||
                       stripos($item['category_code'], $searchLower) !== false;
            });
        }

        // Comment: Format data for Select2 response with category code and dot format (tab. name)
        $results = [];
        foreach ($products as $prod) {
            // Comment: Build display name in format: category_code. name (generic_name)
            $displayName = '';
            if (! empty($prod['category_code'])) {
                $displayName .= $prod['category_code'].'. ';
            }

            $displayName .= $prod['name'];

            if (! empty($prod['generic_name'])) {
                $displayName .= ' ('.$prod['generic_name'].')';
            }

            $results[] = [
                'id' => $prod['id'],
                'text' => $displayName,
                'medicine_name' => $displayName,
                'generic_name' => $prod['generic_name'] ?? '',
            ];
        }

        return response()->json([
            'results' => array_values($results),
        ]);
    }
}
