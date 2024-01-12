<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
//use App\Http\Controllers\Notification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CreateOrder;
class orderController extends Controller
{




    public function show_all_orders_to_warehouse()//done
    {
        $user = auth()->user();
        $id = $user->id;

        $order = Order::where('warehouse_id', $id)
        ->select('id','pharmacy_name', 'year', 'month','status','pay_status')
        ->get();
        $message = "this is the all orders";

        return response()->json([
            'status' => 1,
            'message' => $message,
            'data' => $order,
        ]);
    }


    public function show_all_orders_to_pharmacy()//done
    {
        $user = auth()->user();
        $id = $user->id;

        $order = Order::where('user_id', $id)
        ->select('id','warehouse_name','status','pay_status', 'year', 'month')
        ->get();
        $message = "this is the all orders";

        return response()->json([
            'status' => 1,
            'message' => $message,
            'data' => $order,
        ]);
    }


    public function show_one_order_to_warehouse($order_id)//done
{
    $user = auth()->user();
    $id = $user->id;
    $order = Order::where('warehouse_id', $id)
                        ->where('id', $order_id)
                        ->get();

    if (is_null($order)) {
        $message = "The order doesn't exist.";
        return response()->json([
            'status' => 0,
            'message' => $message,
        ]);
    }

    $message = "This is the order.";
    return response()->json([
        'status' => 1,
        'message' => $message,
        'data' => $order,
    ]);
}

public function show_one_order_to_pharmacy($order_id)//done
{
    $user = auth()->user();
    $id = $user->id;
    $order = Order::where('user_id', $id)
                        ->where('id', $order_id)
                        ->get();

    if (is_null($order)) {
        $message = "The order doesn't exist.";
        return response()->json([
            'status' => 0,
            'message' => $message,
        ]);
    }

    $message = "This is the order.";
    return response()->json([
        'status' => 1,
        'message' => $message,
        'data' => $order,
    ]);
}


public function create_order(request $request)
  {
    $user = auth()->user();
    $id = $user->id;
    if(!$user->admin){
        $request->validate([
            'warehouse_id'=>'required',
            'content'=>'required|array',
            'content.*.product_id'=>'required|integer',
            'content.*.name_scientific'=>'required|string',
            'content.*.quantity'=>'required|integer|min:1'
        ],
        ['content.required'=>'there is no content in the order']);
        $warehouse_id = $request->warehouse_id;
        $content = $request->content;
        foreach($content as $item){
            $product = Product::where('warehouse_id', $warehouse_id)
                    ->where('id', $item['product_id'])
                    ->first();
                    if (!$product) {
                        return response()->json([
                            'status' => 0,
                            'message' => 'Product with ID ' . $item['product_id'] . ' not found in warehouse'
                        ]);
                    }
            if ($item['quantity'] < 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'invalid quantity for product with ID ' . $item['product_id'],
                ]);
            } else if ($item['quantity'] > $product->quantity) {
                return response()->json([
                    'status' => 0,
                    'message' => 'quantity exceeds available stock for product with ID ' . $item['product_id'],
                ]);
            } else {
                $product->quantity -= $item['quantity'];
                $product->save();
            }
        }

            }
            $currentDate = Carbon::now();
            $year = $currentDate->year;
            $month = $currentDate->month;

           $warehouse_name=User::where('id',$request->warehouse_id)->get('name')->first();
           $pharmacy_name=User::where('id',$id)->get('name')->first();
        Order::create([
            'user_id'=>$id,
            'pharmacy_name'=> $pharmacy_name->name,
            'status'=>'pending',
            'pay_status'=>'pending',
            'warehouse_id'=>$request->warehouse_id,
            'warehouse_name'=> $warehouse_name->name,
            'content'=> json_encode($request->content),
            'year' => $year,
            'month' => $month,
        ]);

        return response()->json(
            [
                'status'=>1,
                'message'=>'orders created successfully',
                'data'=>$request->content
            ]
        );
    }


  public function edit_order_warehouse(Request $request,$order_id)//done
  {

    $user = auth()->user();
    $id = $user->id;
    if ($user->admin) {
        $order = Order::where('warehouse_id', $id)->find($order_id);
        if (!$order) {
            $message = "The order does not exist or you are not authorized to update this order.";
            return response()->json([
                'status' => 0,
                'message' => $message,
            ]);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'required',
            'pay_status' => 'required'
        ]);

        if ($validator->fails()) {
            $message = "There is an error in the inputs.";
            return response()->json([
                'status' => 0,
                'message' => $message,
                'data' => $input,
            ]);
        }

        $order->status = $input['status'];
        $order->pay_status = $input['pay_status'];
        $order->year = Carbon::now()->year;
        $order->month = Carbon::now()->month;

        $content = $order->content;
        if ($order->status === 'done' && $order->pay_status === 'done') {
            $report = new Report();
            $report->pharmacy_id = $order->user_id;
            $report->warehouse_id = $id;
            $report->pharmacy_name = $order->pharmacy_name;
            $report->warehouse_name = $user->name;
            $report->month = $order->month;
            $report->year = $order->year;
            $report->content = json_encode($content);
            $report->save();
        }

        $order->save();



        $message = "The order has been updated successfully.";
        return response()->json([
            'status' => 1,
            'message' => $message,
            'data' => $order
        ]);
    }
  }





  public function edit_order_pharmacy(Request $request,$order_id)
  {
    $user = auth()->user();
    $id = $user->id;

    if (!$user->admin) {
        $order = Order::where('user_id', $id)->find($order_id);

        if ($user->id !== $order['user_id']) {
            $message = "You are not authorized to update this order.";
            return response()->json([
                'status' => 0,
                'message' => $message,
            ]);
        }

        if ($order->status !== 'pending') {
            $message = "you cant edit the order because Delivery is in progress.";
            return response()->json([
                'status' => 0,
                'message' => $message,
            ]);
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            $message = "There is an error in the inputs.";
            return response()->json([
                'status' => 0,
                'message' => $message,
                'data' => $input,
            ]);
        }
        $oldContent = json_decode($order->content, true);
        foreach ($oldContent as $item) {
            $product = Product::find($item['product_id']);
            $product->quantity += $item['quantity'];
            $product->save();
        }



        foreach($request->content as $item){
            $product = Product::find($item['product_id']);
            if ($item['quantity'] < 0 || $item['quantity'] > $product->quantity) {
                return response()->json([
                    'status' => 0,
                    'message' => 'error in quantity for product with ID ' . $item['product_id'],
                ]);
            }
        }


        $order->content = $input['content'];
        $order->year = Carbon::now()->year;
        $order->month = Carbon::now()->month;
        $order->save();

        foreach ($request->content as $item) {
            $product = Product::find($item['product_id']);
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        $message = "The order has been updated successfully.";
        return response()->json([
            'status' => 1,
            'message' => $message,
            'data' => $order
        ]);
    }
  }



//ARRAY SHOULD BE EMPTY, ADDING DATA TO RESPONSE
  public function delete_order_to_warehouse($id_order)//done
  {
      $user = auth()->user();
      $id = $user->id;
      $order = Order::where('warehouse_id', $id)->find($id_order);
      if (is_null($order)) {
          $message = "The order doesn't exist.";
          return response()->json([
              'status' => 0,
              'message' => $message,
              'data' => [],
          ]);
      }
      $order->delete();
      $message = "The order deleted successfully.";
       return response()->json([
      'status' => 1,
      'message' => $message,
      'data' => $order,
  ]);

  }



  public function delete_order_to_pharmacy($id_order)
  {
      $user = auth()->user();
      $id = $user->id;
      $order = Order::where('user_id', $id)->find($id_order);
      if (is_null($order)) {
          $message = "The order doesn't exist.";
          return response()->json([
              'status' => 0,
              'message' => $message,
              'data' => [],
          ]);
      }
      if ($order->status == 'pending') {
      $order->delete();
      $message = "The order deleted successfully.";
       return response()->json([
      'status' => 1,
      'message' => $message,
      'data' => $order,
  ]);
      }else{
        $message = "you cant edit the order because Delivery is in progress.";
        return response()->json([
       'status' => 1,
       'message' => $message,
       'data' => [],
   ]);
      }
  }




}
