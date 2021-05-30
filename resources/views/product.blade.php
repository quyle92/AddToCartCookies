@extends('layout.index')
@section('content')
 	
	<product-page  :sizes={{$sizes}} :colors={{$colors}} :price-range-on-init={{$priceRange}}></product-page>

@endsection 

@push('scripts')
<script>
	window.product  = JSON.parse( htmlDecode("{{$product}} ") ); 
	window.totalQuantity = "{{$totalQuantity}}"
</script>
@endpush
