@extends('layout.index')
@section('content')
 	
	<product-page  :sizes={{$sizes}} :colors={{$colors}} :style-id="{{$style_id}}"></product-page>

@endsection 

@push('scripts')
<script>

</script>
@endpush
