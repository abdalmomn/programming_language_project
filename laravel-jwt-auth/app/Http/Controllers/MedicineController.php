<?php

namespace App\Http\Controllers;

use App\Cart;
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
        $id = Auth::guard('api')->user()->id;//admin guard
        
        $medicine = Medicine::where('user_id',$id)->get([
        'id',
        'theScientificName' ,
        'tradeName',
        'category',
        'theManufactureCompany',
        'quantity',
        'validity',
        'price',
        'user_id'
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
    
//     public function order(Request $request)
//     {
//     $userId = Auth::guard('api')->user()->id;
//     $validator = Validator::make($request->all() , [
//         'tradeName' => 'required',
//         'quantity' => 'required|integer|min:1',
//         ]);
//     if ($validator->fails()) {
//         return response()->json($validator->errors() , 422);
//     }
//     $medicine = $request->only(['tradeName', 'quantity', 'user_id']);
//     $warehouse_data = Medicine::where('tradeName', $medicine['tradeName'])->first();
    
        
    
//     if ($warehouse_data && $warehouse_data->quantity >= $medicine['quantity']) {
    
//         $warehouse_data->decrement('quantity', $medicine['quantity']);
//         $existingOrder = Order::where('tradeName', $medicine['tradeName'])->latest()->first();

        
//         if ($existingOrder) {
//             $existingOrder->increment('quantity', $medicine['quantity']);
//         } else {
//             Order::create([
//             'tradeName' => $medicine['tradeName'],
//             'quantity' => $medicine['quantity'],
//             'user_id' => $userId, 
//     ]);
//         }
//     } else if(!$warehouse_data){
//         return response()->json([
//             'error' => 'The medicine of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
//         ]);
//     }else{
//         return response()->json([
//             'error' => 'The quantity of ' . $medicine['tradeName'] . ' is not available in the warehouse.'
//         ]);
//     }
//     return response()->json([
//         'success' => 'order has been added to cart',
//     ]);
// }
    public function order(Request $request){
        $userId = Auth::guard('api')->user()->id;
        
        $validator = Validator::make($request->all() , [
        
        'tradeName' => 'required',
        'quantity' => 'required',
        ]);
        if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
        }
        
        $cart = new Cart();
        $cart->tradeName = $request->tradeName;
        $cart->quantity = $request->quantity;
        $cart->save();
        
        
        $order = new Order();
        $order->user_id = $userId;
        $order->save();
        
        $order->id;
        
    }
    
    
    
    
    
    public function store(Request $request)
    {
    $order = new Order();
    $order->save();
    
    $order->medicines()->attach($request->input('medicines'), ['quantity' => $request->input('quantity')]);
    
    // Additional logic for storing other order details
    
    return redirect()->route('orders.index')->with('success', 'Order created successfully.');
}

    
    public function viewUserOrders()
    {
    $userId = Auth::guard('api')->user()->id;
        
        $ide = Medicine::where($userId , 'user_id')->get('user_id');
        $admin = Order::where('user_id' , $ide)->get('user_id');
        return $admin;
        
    $orders = Order::where('user_id', $userId)->get([
        'id',
        'tradeName',
        'quantity',
        'status',
        'payment_status',
        
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
    public function viewAdminOrders()
    {
    $userId = Auth::guard('api')->user()->id;
    
    $orders = Order::where('user_id', '=' , $userId)->get([
        'id',
        'tradeName',
        'quantity',
        'status',
        'payment_status',
        'user_id',
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
