<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // =====================================================================
    // 1. ENDPOINT UTAMA UNTUK PENGGUNA (SEARCH, FILTER, BROWSE)
    // =====================================================================
    public function index(Request $request)
    {
        $query = Product::with(['category', 'shop']);

        // Fitur Pencarian: Cari berdasarkan nama produk (ilike = case insensitive di PostgreSQL)
        if ($request->has('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        // Fitur Filter: Berdasarkan Kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Fitur Filter: Berdasarkan Rentang Harga
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->get();

        return response()->json(['status' => 'success', 'data' => $products], 200);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'shop'])->find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    // =====================================================================
    // 2. ENDPOINT UNTUK PENJUAL (CRUD)
    // =====================================================================
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $product = Product::create($request->all());

        return response()->json(['status' => 'success', 'message' => 'Product created successfully', 'data' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->update($request->all());

        return response()->json(['status' => 'success', 'message' => 'Product updated successfully', 'data' => $product], 200);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
    }

    // =====================================================================
    // 3. ENDPOINT KHUSUS MICROSERVICES (CART & ORDER)
    // =====================================================================
    
    // Cart-Service akan memanggil ini untuk mendapat detail spesifik tanpa data berat/berlebih
    public function getForMicroservice($id)
    {
        $product = Product::select('id', 'name', 'price', 'weight', 'stock', 'shop_id')->find($id);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    // Order-Service akan mengirim POST JSON kesini saat user berhasil bayar
    public function deductStock(Request $request)
    {
        $items = $request->input('items'); // Format ekspektasi: [ {"product_id": 1, "quantity": 2} ]

        if (!$items) {
            return response()->json(['status' => 'error', 'message' => 'No items provided'], 400);
        }

        // Mulai Transaksi Database (Mencegah "Race Condition" jika 2 orang beli barang yang sama bersamaan)
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                // lockForUpdate() mengunci baris ini sementara waktu hingga proses selesai
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Product ID {$item['product_id']} not found");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for Product: " . $product->name);
                }

                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Stock deducted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua pemotongan jika ada 1 saja barang yang stoknya kurang
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}