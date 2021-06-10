@extends('layout.index')
@section('content')
 	
	<section class="shop checkout section">
		<div class="container">
			<div class="row"> 
				<div class="col-lg-8 col-12">
					<div class="checkout-form">
						<h2>Make Your Checkout Here</h2>
						<p>Please register in order to checkout more quickly</p>
						<!-- Form -->
						<form class="form" method="post" action="/checkout/payment/paypal" id="checkoutForm">
							{{csrf_field()}}
							<div class="row">
								<div class="col-lg-12 col-md-12 col-12">
									<div class="form-group">
										<label>First Name<span>*</span></label>
										<input type="text" name="customer_name" placeholder="" required="required" value="{{$faker->name}}">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-12">
									<div class="form-group">
										<label>Email Address<span>*</span></label>
										<input type="email" name="customer_email" placeholder="" required="required" value="{{$faker->companyEmail}}">
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-12">
									<div class="form-group">
										<label>Phone Number<span>*</span></label>
										<input type="text" name="customer_phone" placeholder="" required="required" value="{{$faker->phoneNumber}}">
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-12">
									<div class="form-group">
										<label>Address Line 1<span>*</span></label>
										<input type="text" name="customer_address" placeholder="" required="required" value="{{$faker->streetAddress, $faker->city }}">
									</div>
								</div>

								<div class="col-lg-6 col-md-6 col-12">
									<div class="form-group">
										<label>Postal Code<span>*</span></label>
										<input type="text" name="post" placeholder="" required="required" value="{{$faker->postcode }}" >
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-12">
									<div class="form-group">
										<label>Company<span>*</span></label>
										<select name="company_name" id="company">
											<option value="company" selected="selected">Microsoft</option>
											<option>Apple</option>
											<option>Xaiomi</option>
											<option>Huawer</option>
											<option>Wpthemesgrid</option>
											<option>Samsung</option>
											<option>Motorola</option>
										</select>
									</div>
								</div>
								<div class="col-12">
									<div class="form-group create-account">
										<input id="cbox" type="checkbox">
										<label>Create an account?</label>
									</div>
								</div>
							</div>
						</form>
						<!--/ End Form -->
					</div>
				</div>
				<div class="col-lg-4 col-12">
					<div class="order-details">
						<!-- Order Widget -->
						<div class="single-widget">
							<h2>CART  TOTALS</h2>
							<div class="content">
								<ul>
									<!-- <li>item<span>$10.00</span><br><small>2 x $5</small></li> -->
									<li class="last">Sub Total<span id="subtotal">$330.00</span></li>
									<li>(+) Shipping<span id="shipping">$10.00</span></li>
									<li class="last" id="grandTotal">Total<span></span></li>
								</ul>
							</div>
						</div>
						<!--/ End Order Widget -->

						<!-- Order Widget -->
						<div class="single-widget">
							<h2>Payments</h2>
							<div class="content">
								<div class="checkbox">
									@foreach($payment_methods as $item)
									<label class="checkbox-inline" for="{{$item['payment_type']}}">
										<input name="paymentMethod[]" id="{{$item['payment_type']}}" type="checkbox" value="{{$item['payment_type']}}">
										@inject('Str', 'Illuminate\Support\Str')
									 	{{Str::title($item['payment_type'])}}
									</label>
									@endforeach
									<!-- <label class="checkbox-inline" for="2"><input name="paymentMethod[]" id="2" type="checkbox" value="cod"> Cash On Delivery</label>
									<label class="checkbox-inline" for="3"><input name="paymentMethod[]" id="3" type="checkbox" value="paypal"> PayPal</label> -->
								</div>
							</div>
						</div>
						<!--/ End Order Widget -->
						<!-- Payment Method Widget -->
						<div class="single-widget payement">
							<div class="content">
								<img src="{{ asset('/images/payment-method.png') }}	" alt="#">
							</div>
						</div>
						<!--/ End Payment Method Widget -->
						<!-- Button Widget -->
						<div class="single-widget get-button">
							<div class="content">
								<div class="button" style="background: #333">
									<a href="#" class="btn">proceed</a>
								</div>
							</div>
						</div>
						<!--/ End Button Widget -->
					</div>
				</div>
			</div>
		</div>
<!-- <form action="/checkout/payment/paypal" method="post">
	{{csrf_field()}}
    <input type="text" name="amount" value="20.00" />
    <input type="submit" name="submit" value="Pay Now">
</form> -->
	</section>
<?php if(isset($message)) dd($message); ?>
@endsection 

@push('scripts')
<script>


$(function() {
	const form = $('#checkoutForm');
	let products =  JSON.parse( localStorage.getItem('products') ) ?? [];
	let itemList = '';
	let subTotal = 0;
	products.forEach( e => {
		let amount = +e.price * +e.quantity;
		itemList += `<li>${e.style} | ${e.fullNumber}<span>$${amount.toFixed(2)}</span><br><small>${e.quantity} x $${e.price}</small></li>`;
		subTotal += amount;
	});
	$('.content ul').prepend(itemList);

	//get grandTotal
	$('#subtotal').text(`$${subTotal.toFixed(2)}`)
	subTotal = $('#subtotal').text();
	let shipping = $('#shipping').text();

	subTotal = subTotal.replace('$', '');
	shipping = shipping.replace('$', '');

	let grandTotal = +subTotal + +shipping
	$('#grandTotal span').text( `$ ${grandTotal.toFixed(2)}` );

	//select preferred payment method
	var inputs = $('.checkbox').find('input');
	$( "body" ).on( "click", ".checkbox input", function( event ) {
		
		inputs.each(function( index ) {
		  $(this).parent("label").removeClass('checked');
		});
		$(this).parent("label").addClass('checked')	
	});

	//submit 
	$('.button').click( function(e) {
		e.preventDefault();
		let inputChecked = $('.checkbox input:checkbox:checked');
		let isChecked = inputChecked.length > 0;

		if(isChecked) 
		{	console.log(form);
			var clientInfo= form.serialize();
		  	const searchParams = new URLSearchParams(clientInfo);
		  	clientInfo = Object.fromEntries(searchParams);
		  	$.ajax({
		  		method: 'post',
		  		headers: {
		  			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  		},
		  		url: '{{route('app.checkProducts')}}',
		  		data: {
		  			// clientInfo:  clientInfo,
		  			products: products,
		  			grandTotal: grandTotal
		  		},
		  		success: function(response) {
		  			let result = response?.message.product_not_available ?? undefined;
		  			//if( response.message.hasOwnProperty('product_not_available') ) (1)
		  			if( result !== undefined ) 
		  			{
		  				alert(response.message.product_not_available);
		  			}
		  			
		  			if( response.message.hasOwnProperty('out_of_stock') )
		  			{
		  				let products = response.message.out_of_stock;
		  				let message = [];
		  				products.forEach( e => {
		  					message.push(`${e.fullNumber} has only ${e.quantityLeft}  left`) 
		  				})
		  				//console.log(products)
		  				alert(message.toString());
		  			}

		  			if(response.message === "success")
			  		{
			  			switch( inputChecked.val() ) 
			  			{
							case 'cod':
							   	form.attr('action', '/checkout/payment/cod');
							   	form.submit();
							   	
							    break;
							case 'bank transfer':
							    // code block
							    break;
							case 'paypal':
					  			form.submit();
				  			
						    break;
						    
						}
					}
					
		  		}
		  	});
		  	
		}


	})

});

/*
note
*/
//(1) this is solution 2 of the above.
</script>
@endpush
