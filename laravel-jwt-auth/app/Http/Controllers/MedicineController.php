<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\orderStatus;
use App\Models\status;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class MedicineController extends Controller
{
    
    public function showMedicines(){
        $id = Auth::guard('api')->user()->id;
        
        $medicine = Medicine::where('user_id',$id)->get([
        'id',
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
    ]);
    
    return $medicine;
}

public function insert(Request $request){
    $request['user_id'] = Auth::guard('api')->user()->id;
    $validator = Validator::make($request->all() , [
        'theScientificName' => 'required',
        'tradeName' => 'required',
        'category' => 'required',
        'theManufactureCompany' => 'required',
        'quantity' => 'required',
        'validity' => 'required',
        'price' => 'required',
    ]);
    if($validator->fails()){
        return response()->json($validator->errors(), 422);
    }
    $medicine = Medicine::Create($request->all());
    return response()->json([
        'message' => 'inserted successfully',
        'medicine ' => $medicine
    ]);
    }
    
    
    public function details( request $request){
    $det=Medicine::where('id',$request->id)->get([
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
    ]);
    return $det;
    }
    public function search(request $request){
        $search=$request->search;
        $data=Medicine::where('TradeName' , 'like','%'. $search.'%')->orwhere('category' , 'like','%'. $search.'%')->get(['tradeName' , 'category']);
        return response()->json([
            'data' => $data,
            ]);
    }
    
    public function order(Request $request)
    {
    $userId = Auth::guard('api')->user()->id;

    $validator = Validator::make($request->all() , [
        'tradeName' => 'required',
        'quantity' => 'required|integer|min:1',
        ]);
    
    if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
    }
    
    
    $medicine = $request->only(['tradeName', 'quantity']);

    $warehouse_data = Medicine::where('tradeName', $medicine['tradeName'])->first();

    if ($warehouse_data && $warehouse_data->quantity >= $medicine['quantity']) {
    
        $warehouse_data->decrement('quantity', $medicine['quantity']);
        $existingOrder = Order::where('tradeName', $medicine['tradeName'])->latest()->first();

        if ($existingOrder) {
            $existingOrder->increment('quantity', $medicine['quantity']);
        } else {
            Order::create([
            'tradeName' => $medicine['tradeName'],
            'quantity' => $medicine['quantity'],
            'user_id' => $userId, 
    ]);
        }
    } else if(!$warehouse_data){
        return response()->json([
            'error' => 'The medicine of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
        ]);
    }else{
        return response()->json([
            'error' => 'The quantity of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
        ]);
    }

    return response()->json([
        'success' => 'order has been added to cart'
    ]);
}
    
    public function viewOrders()
    {
    $userId = Auth::guard('api')->user()->id;
    
    $orders = Order::where('user_id', $userId)->get(['tradeName',
        'quantity',
        'status',
        'purchase'
        ]);
        if ($orders) {
            return response()->json([
            "data" => $orders
            ]);
            }else {
            return response()->json([
            "message" => "the order is not found in our data",  
            ]);
        }
}
    public function updateOrderStatus(Request $request, $orderId)
    {   
    $userId = Auth::guard('api')->user()->id;
    $order = Order::find($orderId);
    
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }
    $validator = Validator::make($request->all(), [
        'status' => 'required|in:' . implode(',', [Order::STATUS_SENT, Order::STATUS_RECEIVED]),
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    $order->update(['status' => $request->input('status')]);
    
    return response()->json(['success' => 'Order status updated successfully']);
}


    public function updateOrderPaymentStatus(Request $request, $orderId)
{
    $userId = Auth::guard('api')->user()->id;
    $order = Order::find($orderId);
    
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }
    $validator = Validator::make($request->all(), [
        'payment_status' => 'required|in:' . implode(',', [Order::PAYMENT_UNPAID, Order::PAYMENT_PAID]),
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }
    $order->update(['payment_status' => $request->input('payment_status')]);

    return response()->json(['success' => 'payment status updated successfully']);
}

}
