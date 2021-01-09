<div>
    <div class="-mx-4 px-4 py-4 overflow-x-auto">
        <div class="inline-block min-w-full shadow overflow-hidden">
            <table class="min-w-full leading-normal border-2 border-indigo-200">
                <thead>
                <tr>
                    <th
                        class="px-2 py-2 border-b-2 border-indigo-200 bg-indigo-600 text-left text-sm font-semibold text-white uppercase tracking-wider">
                        {{ trans('custom.item_name') }}
                    </th>
                    <th
                        class="px-2 py-2 border-b-2 border-indigo-200 bg-indigo-600 text-left text-sm font-semibold text-white uppercase tracking-wider">
                        {{ trans('custom.item_price') }}
                    </th>
                    <th
                        class="px-2 py-2 border-b-2 border-indigo-200 bg-indigo-600 text-left text-sm font-semibold text-white uppercase tracking-wider">
                        {{ trans('custom.item_quantity') }}
                    </th>
                    <th
                        class="px-2 py-2 border-b-2 border-indigo-200 bg-indigo-600 text-left text-sm font-semibold text-white uppercase tracking-wider">
                        {{ trans('custom.item_summary') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @include('cash.components.checkout-item', [
                        'item' => $item['data'],
                        'quantity' => $item['quantity']
                    ])
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3"
                        class="px-2 py-2 bg-indigo-600 text-left text-sm font-semibold text-white uppercase tracking-wider">

                    </th>
                    <th
                        class="px-2 py-2 bg-indigo-600 text-left text-sm font-bold text-white uppercase tracking-wider">
                        {{ $total_price }} {{ config('settings.currency') }}
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
