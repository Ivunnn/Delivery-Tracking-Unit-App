@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.ecommerce-metrics
                :customer-count="$customerCount"
                :order-count="$orderCount"
                :unit-count="$unitCount"
                :driver-count="$driverCount"
                :revenue="$revenue"
            />
        </div>

        <div class="col-span-12 xl:col-span-7">
            <x-ecommerce.recent-orders :products="$recentOrders" />
        </div>
    </div>
@endsection