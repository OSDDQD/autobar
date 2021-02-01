<?php

namespace App\Cashbox\Http\Controllers;

use App\Cashbox\Models\Income;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use App\Cashbox\Models\Wallet;
use Illuminate\Http\Request;
use App\Cashbox\Models\Cart;
use Illuminate\Support\Facades\Cache;
use App\Cashbox\Models\Order;
use App\Cashbox\Models\OrderItem;
use App\Cashbox\Models\Item;
use App\Cashbox\Models\Category;

class WalletController extends Controller
{
    const SECRET_KEY = 'VALIDATOR_SECRET';

    public function test()
    {
        $items = Item::active()->with(['activeOptions' => function ($query) {
            $query
                ->selectRaw('(SELECT IFNULL(sum(incomes.quantity), 0) FROM incomes WHERE incomes.option_id = options.id)
                    - (SELECT IFNULL(sum(order_items.quantity), 0) FROM order_items WHERE order_items.option_id = options.id)
                as total, options.*')
                ->whereRaw('(SELECT IFNULL(sum(incomes.quantity), 0) FROM incomes WHERE incomes.option_id = options.id)
                    - (SELECT IFNULL(sum(order_items.quantity), 0) FROM order_items WHERE order_items.option_id = options.id)
                > 0');
        }])->get();
        $category = Category::active()
            ->with('items')
            ->orderBy('lft')
            ->findOrFail(7);
        dd($category->items);
//        return $items;
    }

    public function add(Request $request)
    {
        $request->validate([
            'secret' => [
                'required',
                Rule::in([self::SECRET_KEY]),
            ],
            'value' => [
                'required',
                Rule::in(Wallet::getChannels()),
            ],
        ]);

        if(Cart::getTotalPrice() > Wallet::getCurrentSum()) {
            Wallet::addSum($request->get('value'));

            \App\Cashbox\Models\Manager::send([
                'event' => 'creditAdded',
            ]);

            if(!Cache::has(Order::CACHE_KEY)) {
                $order = Order::create([
                    'status' => Order::STATUS_PENDING,
                ]);

                $items = [];
                foreach (Cart::getItems() as $item) {
                    if(isset($item['options'])) {
                        foreach ($item['options'] as $option) {
                            $items[] = new OrderItem([
                                'item_id' => $item['data']->id,
                                'quantity' => $option['quantity'],
                                'option_id' => $option['data']->id,
                                'price' => $option['data']->price ?? $item['data']->price
                            ]);
                        }
                    } else {
                        $items[] = new OrderItem([
                            'item_id' => $item['data']->id,
                            'quantity' => $item['quantity'],
                            'price' => $item['data']->price
                        ]);
                    }
                }

                $order->items()->saveMany($items);

                Cache::set(Order::CACHE_KEY, $order->id);

                \App\Cashbox\Models\Manager::send([
                    'event' => 'orderCreated',
                    'url' => route('manager.show')
                ]);
            }

//            $order = Order::find(Cache::get(Order::CACHE_KEY));
//            $order->paid = Wallet::getCurrentSum();
//            $order->save();
        }

        if(Cart::getTotalPrice() <= Wallet::getCurrentSum()) {
            \App\Cashbox\Models\Manager::send([
                'event' => 'orderPaid'
            ]);

            Wallet::send([
                'action' => 'STOP'
            ]);
        }

        return response()->json([
            'value' => $request->get('value'),
            'current_sum' => Wallet::getCurrentSum(),
            'total_price' => Cart::getTotalPrice(),
        ]);
    }
}
