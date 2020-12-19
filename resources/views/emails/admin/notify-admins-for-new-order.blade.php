@component('mail::message')
# New order

User {{$details->user}} ordered this products :

@foreach($details->products as $product)
    -{{$product}}
@endforeach

@component('mail::button', ['url' => route('admin.products.index')])
Check it here
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
