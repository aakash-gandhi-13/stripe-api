<?php

namespace App\Http\Controllers;

use App\Traits\CustomResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    use CustomResponse;

    protected $stripe;
    protected $stripeBaseUrl;

    public function __construct()
    {
        $this->stripeBaseUrl = 'https://api.stripe.com/';
        $this->stripe = new StripeClient(env("STRIPE_API_KEY"));
    }

    // Customer APIs
    public function listCustomer()
    {
        $response = $this->stripe->customers->all(['limit' => 250]);
        $customers = $response->data;
        
        return response()->json($customers, 200);
    }

    public function readCustomer(Request $request)
    {
        try {
            $customer = $this->stripe->customers->retrieve($request->customer_id, []);
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function createCustomer(Request $request)
    {
        $params = $request->all();
        try {
            $customer = $this->stripe->customers->create($params);
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updateCustomer(Request $request)
    {
        $params = $request->except('customer_id');
        $customerId = $request->customer_id;
        try {
            $customer = $this->stripe->customers->update(
                $customerId,
                $params
              );
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
    
    public function deleteCustomer(Request $request)
    {
        $customerId = $request->customer_id;
        try {
            $customer = $this->stripe->customers->delete(
                $customerId
              );
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    
    // Products API's
    public function listProduct()
    {
        $response = $this->stripe->products->all(['limit' => 250]);
        $customers = $response->data;
        
        return response()->json($customers, 200);
    }

    public function readProduct(Request $request)
    {
        try {
            $product = $this->stripe->products->retrieve($request->product_id, []);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function createProduct(Request $request)
    {
        $params = $request->all();
        try {
            $product = $this->stripe->products->create($params);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updateProduct(Request $request)
    {
        $params = $request->except('product_id');
        $productId = $request->product_id;
        try {
            $product = $this->stripe->products->update(
                $productId,
                $params
              );
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }
    
    public function deleteProduct(Request $request)
    {
        $productId = $request->product_id;
        try {
            $product = $this->stripe->products->delete(
                $productId
              );
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    // Price APIs, Price is attached to the product
    public function listPrice(Request $request)
    {
        $params = $request->all();
        $response = $this->stripe->prices->all($params);
        $prices = $response->data;
        
        return response()->json($prices, 200);
    }

    public function readPrice(Request $request)
    {
        try {
            $price = $this->stripe->prices->retrieve($request->price_id, []);
            return response()->json($price, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function createPrice(Request $request)
    {
        $params = $request->all();
        try {
            $price = $this->stripe->prices->create($params);
            return response()->json($price, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updatePrice(Request $request)
    {
        $params = $request->except('price_id');
        $priceId = $request->price_id;
        try {
            $price = $this->stripe->prices->update(
                $priceId,
                $params
              );
            return response()->json($price, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    // Setup intent
    public function createSetupIntent(Request $request)
    {
        $customerId = $request->customer_id;
        try {
            $customer = $this->stripe->setupIntents->create(
                [
                    'customer' => $customerId,
                    'payment_method_types' => ['card'],
                  ]
              );
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    //Subscription

    public function listSubscription(Request $request)
    {
        $params = $request->all();
        $response = $this->stripe->subscriptions->all($params);
        $subscriptions = $response->data;
        
        return response()->json($subscriptions, 200);
    }

    public function readSubscription(Request $request)
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($request->subscription_id, []);
            return response()->json($subscription, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function createSubscription(Request $request)
    {
        $customerId = $request->customer_id;
        $priceId = $request->price_id;
        try {
            $customer = $this->stripe->subscriptions->create([
                'customer' => $customerId,
                'items' => [
                  ['price' => $priceId],
                ],
                "collection_method" => "charge_automatically",
                'payment_behavior' => 'default_incomplete',
                'expand' => ['latest_invoice.payment_intent'],
              ]);
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updateSubscription(Request $request)
    {
        $params = $request->except('subscription_id');
        $subscriptionId = $request->subscription_id;
        try {
            $subscription = $this->stripe->subscriptions->update(
                $subscriptionId,
                $params
              );
            return response()->json($subscription, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    //Payment Method APIs
    public function listPaymentMethod(Request $request)
    {
        $params = $request->all();
        $response = $this->stripe->paymentMethods->all($params);
        $paymentMethods = $response->data;
        
        return response()->json($paymentMethods, 200);
    }

    public function readPaymentMethod(Request $request)
    {
        try {
            $paymentMethod = $this->stripe->paymentMethods->retrieve($request->payment_method_id, []);
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function createPaymentMethod(Request $request)
    {
        $params = $request->all();
        try {
            $paymentMethod = $this->stripe->paymentMethods->create($params);
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        $params = $request->except('price_id');
        $priceId = $request->price_id;
        try {
            $paymentMethod = $this->stripe->paymentMethods->update(
                $priceId,
                $params
              );
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function attachPaymentMethodToCustomer(Request $request)
    {
        $params = $request->except('payment_method_id');
        $paymentMethodId = $request->payment_method_id;
        try {
            $paymentMethod = $this->stripe->paymentMethods->attach(
                $paymentMethodId,
                $params
              );
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function detachPaymentMethodToCustomer(Request $request)
    {
        $paymentMethodId = $request->payment_method_id;
        try {
            $paymentMethod = $this->stripe->paymentMethods->detach(
                $paymentMethodId,
                []
              );
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }


    // Create Session for Checkout & Payment links work with frontend

    public function createSession(Request $request)
    {
        $customerId = $request->customer_id;
        $priceId = $request->price_id;
        $mode = $request->mode; // payment / subscription
        
        try {
            $customer = $this->stripe->checkout->sessions->create([
                'success_url' => env('STRIPE_SUCCESS_URL'),
                'cancel_url' => env('STRIPE_CANCEL_URL'),
                'customer' => $customerId,
                'line_items' => [
                    [
                    'price' => $priceId,
                    'quantity' => 1,
                    ],
                ],
                'mode' => $mode,
              ]);
            return response()->json($customer, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function stripeWebHooks(Request $request)
    {
        //dd($request->all());

        // Handle the event
        // Need to write database / api logic here
        // switch ($event->type) {
        //     case 'customer.subscription.created':
        //     $subscription = $event->data->object;
        //     case 'customer.subscription.updated':
        //     $subscription = $event->data->object;
        //     // ... handle other event types
        //     default:
        //     echo 'Received unknown event type ' . $event->type;
        // }
    }

    public function hookSubscriptionCreated(Request $request)
    {
        $subscriptionId = $request->data['object']['id'];
        $subscriptionStart = $request->data['object']['current_period_start'];
        $subscribtionEnd = Carbon::parse($subscriptionStart)->addDays(90)->getTimestamp();
        
        try {
            $subscription = $this->stripe->subscriptions->update(
                $subscriptionId,
                [
                    // 'cancel_at_period_end' => false,
                'cancel_at' => $subscribtionEnd]
              );
            return response()->json($subscription, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function hookSubscriptionUpdated(Request $request)
    {
        
    }

}
