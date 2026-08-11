<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $productClass = '\App\Models\Product';
        $saleClass = '\App\Models\Sale';
        $transactionClass = '\App\Models\Transaction';

        $hasProducts = class_exists($productClass) && Schema::hasTable('products');
        $hasSales = class_exists($saleClass) && Schema::hasTable('sales');
        $hasTransactions = class_exists($transactionClass) && Schema::hasTable('transactions');
        $hasSaleItems = $hasProducts && $hasSales && Schema::hasTable('sale_items');

        // Product stats
        $totalProducts = $hasProducts ? $productClass::count() : 0;
        $activeProducts = $hasProducts ? $productClass::where('status', 'active')->count() : 0;
        $outOfStockProducts = $hasProducts ? $productClass::where('stock', 0)->count() : 0;
        $lowStockProducts = $hasProducts
            ? $productClass::whereColumn('stock', '<=', 'minimum_stock')
                ->where('stock', '>', 0)
                ->count()
            : 0;

        // Sales stats
        $salesToday = $hasSales
            ? $saleClass::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total')
            : 0;

        $salesMonth = $hasSales
            ? $saleClass::whereMonth('created_at', $today->month)
                ->whereYear('created_at', $today->year)
                ->where('status', 'completed')
                ->sum('total')
            : 0;

        $salesCountToday = $hasSales
            ? $saleClass::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->count()
            : 0;

        // Transactions stats
        $transactionsToday = $hasTransactions
            ? $transactionClass::whereDate('transaction_date', $today)
                ->where('status', 'completed')
                ->count()
            : 0;

        $servicesIncomeToday = $hasTransactions
            ? $transactionClass::whereDate('transaction_date', $today)
                ->where('status', 'completed')
                ->sum('fee')
            : 0;

        // Low stock alerts
        $lowStockList = $hasProducts
            ? $productClass::with('category')
                ->where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->limit(5)
                ->get()
            : collect();

        $outOfStockList = $hasProducts
            ? $productClass::with('category')
                ->where('stock', 0)
                ->where('status', 'active')
                ->limit(5)
                ->get()
            : collect();

        // Best selling products (last 30 days)
        $topProducts = $hasSaleItems
            ? DB::table('sale_items')
                ->join('products', 'sale_items.product_id', '=', 'products.id')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.created_at', '>=', now()->subDays(30))
                ->where('sales.status', 'completed')
                ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_sold'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get()
            : collect();

        return view('dashboard.index', compact(
            'totalProducts',
            'activeProducts',
            'outOfStockProducts',
            'lowStockProducts',
            'salesToday',
            'salesMonth',
            'salesCountToday',
            'transactionsToday',
            'servicesIncomeToday',
            'lowStockList',
            'outOfStockList',
            'topProducts',
        ));
    }
}

