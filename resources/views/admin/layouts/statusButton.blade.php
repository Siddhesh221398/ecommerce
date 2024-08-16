@if($product->is_active == 1)
    <button class="btn btn-danger btn-status" data-id="{{$product->id}}" data-value="0"> In Active</button>
@else
    <button class="btn btn-primary btn-status" data-id="{{$product->id}}" data-value="1"> Active</button>

@endif

