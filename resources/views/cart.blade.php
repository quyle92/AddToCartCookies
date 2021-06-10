@extends('layout.index')
@section('content')
	<?=dd($sizes)?>
	<cart  ></cart>
@endsection 

@push('scripts')
<script>
	window.sizeList  = JSON.parse( htmlDecode("{{$sizes}} ") ); 
	window.colorList = JSON.parse( htmlDecode("{{$colors}} ") ); 

</script>
@endpush