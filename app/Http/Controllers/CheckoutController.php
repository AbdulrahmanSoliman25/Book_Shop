<?php

namespace App\Http\Controllers;

use Mail;
use Cart;
use Session;
use Stripe\Charge;
use Stripe\Stripe;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(Cart::content()->count() == 0)
        {
            Session::flash('info', 'Your cart is still empty. do some shopping');
            return redirect()->back();
        }
        return view('checkout');
    }

    public function pay()
    {
        Stripe::setApiKey("sk_test_ZGiCCWnuhyMCFRc0mCDrHWvy004I6WF6qA");

        $charge = Charge::create([
            'amount' => Cart::total() * 100,
            'currency' => 'usd',
            'description' => 'udemy course practice selling books',
            'source' => request()->stripeToken
        ]);

        Session::flash('success', 'Purchase successfull. wait for our email.');

        Cart::destroy();

        Mail::to(request()->stripeEmail)->send(new \App\Mail\PurchaseSuccessful);

        return redirect('/');
    }
}

