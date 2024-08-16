<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AddCartController extends Controller
{
    public function carts(Request $request)
    {
        return view('user.carts');
    }
    public function checkout(Request $request)
    {
        return view('user.checkout');
    }
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = Cart::where(['product_id'=>$request->product_id,'user_id'=>auth()->id()])->first();
        if(!$cart){
            $cart = new Cart();
            $cart->product_id = $request->product_id;
            $cart->price = $product->price;
            $cart->total_price = $cart->qty*$cart->price;
            $cart->user_id = auth()->id();
            $cart->save();
        }
        return response()->json(['status'=> 'success', 'message' => 'Add to cart Successfully']);
    }

    public function removeCart(Request $request)
    {
        $cart = Cart::where(['product_id'=>$request->product_id,'user_id'=>auth()->id()])->delete();

        return response()->json(['status'=> 'success', 'message' => 'Cart remove Successfully']);
    }
    public function decreaseQty(Request $request)
    {
        $cart = Cart::where(['product_id'=>$request->product_id,'user_id'=>auth()->id()])->first();
        if($cart->qty ==1){
            $cart->delete();
            return response()->json(['status'=> 'success', 'total_price'=>0,'message' => 'Cart remove Successfully' ,'cart'=>0 ]);

        }else{
            $cart->qty-=1;
            $cart->total_price = $cart->qty * $cart->price;
            $cart->save();
            $total_price = Cart::where(['user_id'=>auth()->id()])->sum('total_price');

            return response()->json(['status'=> 'success','total_price'=>$total_price, 'message' => 'Cart remove Successfully' ,'cart'=>$cart->qty ]);
        }
    }
    public function increaseQty(Request $request)
    {
        $cart = Cart::where(['product_id'=>$request->product_id,'user_id'=>auth()->id()])->first();
        $cart->qty+=1;
        $cart->total_price = ($cart->qty+1)*$cart->price;
        $cart->save();
        $total_price = Cart::where(['user_id'=>auth()->id()])->sum('total_price');
        return response()->json(['status'=> 'success','total_price'=>$total_price, 'message' => 'Cart remove Successfully']);
    }

    public function order(Request $request){

        $this->validate($request,[
                'first_name'=>'required',
                'last_name'=>'required',
                'address'=>'required',
                'city'=>'required',
                'country'=>'required',
                'pincode'=>'required',
                'mobile_no'=>'required',
            ]);
        $order = new Order;
        $order->user_id = auth()->id();
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->country = $request->country;
        $order->pincode = $request->pincode;
        $order->mobile_no = $request->mobile_no;
        $order->notes = $request->notes;
        $order->save();

        $carts = auth()->user()->carts()->get();
        $total_price=0;
        foreach ($carts as $cart){
            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $cart->product_id;
            $order_item->price = $cart->price;
            $order_item->qty = $cart->qty;
            $order_item->save();
            $total_price+= $cart->total_price;
            $cart->delete();
        }
        $order->total_price =$total_price;
        $order->save();

        return redirect()->route('homePageh');
    }

}
