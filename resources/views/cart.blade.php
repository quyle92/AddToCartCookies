@extends('layout.index')
@section('content')

	<cart :products={{json_encode($products)}} ></cart>
@endsection 