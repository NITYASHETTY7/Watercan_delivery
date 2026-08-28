@forelse ($orders as $order)
    @include('panel.user.order.include.single', ['order' => $order])
@empty
    <div class="flex flex-col items-center justify-center min-h-[60vh] py-10">
        <img src="{{asset('user/assets/icons/no-order.png')}}" class="w-[50px] h-auto" alt="">
        <p class="text-center text-gray-800 font-normal text-[15px] py-2">No Orders Found!</p>
    </div>
@endforelse