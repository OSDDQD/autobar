@if($items)
    <div>
        <div class="grid grid-cols-2 gap-4">
            @foreach($items as $item)
                {{--{{ dd($item) }}--}}
                @include('cash.components.cart-item', [
                    'item' => $item
                ])
            @endforeach
        </div>
        <div class="flex items-center mx-auto mt-8 place-content-center">
            <div class="flex-1 text-center">
                <a href="{{ route('cart.checkout') }}" class="shadow text-2xl bg-indigo-600 hover:bg-purple-800 text-white uppercase font-bold py-3 px-3 inline-flex items-center">
                    {{ trans('custom.checkout', [
                        'sum' => $total_price,
                        'currency' => config('settings.currency')
                    ]) }}
                </a>
            </div>
        </div>
    </div>
@else
    <div class="max-w-screen-xl mx-auto my-3">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {!! trans('custom.cart_empty', ['url' => route('cash.show')]) !!}
        </h2>
    </div>
@endif