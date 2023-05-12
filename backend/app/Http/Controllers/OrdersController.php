<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //

    public function getList(){
        return Order::getList();
    }

    //

    public function createItem(Request $request){
        return Order::createItem($request);
    }

    //

    public function itemAction(Request $request){
        return Order::itemAction($request);
    }

    //

    public function getItem($id){
        return Order::getItem($id);
    }

    //

    public function updateItem(Request $request, $id){
        return Order::updateItem($request, $id);
    }
}
