@extends('layout.index')
@section('content')
 	
	<product-page  :sizes={{$sizes}} :colors={{$colors}} ></product-page>

@endsection 

@push('scripts')
<script>

	window.product  = JSON.parse( htmlDecode("{{$product}} ") ); 
	window.priceRange  = JSON.parse( htmlDecode("{{$priceRange}}") ); 
	window.totalQuantity = "{{$totalQuantity}}"

</script>
@endpush