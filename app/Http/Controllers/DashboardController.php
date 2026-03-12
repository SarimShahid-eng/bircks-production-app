<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
       $suppliersCount= Supplier::count();
       $customersCount= Customer::count();
       $salesCount= Sale::count();
       $purchaseCount= Purchase::count();
        return view('dashboard',compact('suppliersCount','customersCount','salesCount','purchaseCount'));
    }
    //
}
