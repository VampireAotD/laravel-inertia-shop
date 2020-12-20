@component('mail::message')
    # Thank you for ordering at {{config('app.name')}}!

    Your games :

    @foreach($details->productKeys as $product)
        {{$product->product}} : {{$product->key}}
    @endforeach

@endcomponent
