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
						<form class="form" method="post" action="/checkout/payment/paypal" id="checkoutForm" onsubmit="window.onbeforeunload=null;">
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

								<div class="col-lg-4 col-md-4 col-12">
									<div class="form-group">
										<label>Province<span>*</span></label>
										<input type="text" name="province" id="select-province" >
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-12">
									<div class="form-group">
										<label>Distinct<span>*</span></label>
										<input type="text" name="district" id="select-district">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-12">
									<div class="form-group">
										<label>Ward<span>*</span></label>
										<input type="text" name="ward" id="select-ward">
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
									<li class="last">Sub Total<span id="subTotal">$330.00</span></li>
									<li>(+) Shipping<span id="shipping"></span></li>
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

@endsection 

@push('scripts')
<script>

$(function() {

	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

	const form = $('#checkoutForm');
	let products =  JSON.parse( localStorage.getItem('products') ) ?? [];
	let itemList = '';
	let subTotal = 0;
	products.forEach( e => {
		let amount = +e.price * +e.quantity;
		itemList += `<li>${e.style} | ${e.fullNumber}<span>$${amount.toFixed(2)}</span><br><small>${e.quantity} x $${e.price}</small></li>`;
		subTotal += amount;
		
	});
	let subTotalFormat = subTotal.toFixed(2);
	document.getElementById('subTotal').innerText = `$${subTotalFormat}`;
	window.subTotal = subTotal;

	$('.content ul').prepend(itemList);

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
		if( $('#grandTotal span').text().length === 0 ){
			alert('Please select your location!');
			return;
		} 

		let inputChecked = $('.checkbox input:checkbox:checked');
		let isChecked = inputChecked.length > 0;

		if(isChecked) 
		{	
			var clientInfo= form.serialize();
		  	const searchParams = new URLSearchParams(clientInfo);
		  	clientInfo = Object.fromEntries(searchParams);
		  	$.ajax({
		  		method: 'post',
		  		url: '{{route('app.checkProducts')}}',
		  		data: {
		  			// clientInfo:  clientInfo,
		  			products: products,
		  			subTotal: subTotal
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
							    alert('Not support now!')
							    break;
							case 'paypal':
					  			form.submit();
				  			
						    break;
						    
						}
					}
					
		  		}
		  	});
		  	
		}


	});


	//calculate shipping
	var $selectDistrict, $selectWard;

	var $selectProvince = $('#select-province').selectize({
		plugins: ['remove_button'],
		options: [],
		maxItems: 1,
		preload: true,
		labelField: 'ProvinceName',
		valueField: 'ProvinceID',
		searchField: ['ProvinceName'],
		sortField: 'ProvinceName',
		closeAfterSelect: true,
		create: false,
		placeholder: 'Select your province...',
		load: function(query, callback) {
			$.ajax({
				method : 'get',
				url : 'https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/province',
				headers: {
		        	token:  '503b4b90-c9fb-11eb-bc42-967656021ee1',
		    	},
		        success: function(response) {
		        	 callback(response.data);
		        }
		    	
			});
		},
		render: {
		    item: function(item, escape) {
		    	//get ProvinceName to filter out province name at ajax return of $selectDistrict
		    	 window.ProvinceName = item.ProvinceName;
		      return '<div>' +
		        (item.ProvinceName ? '<span class="name">' + escape(item.ProvinceName) + '</span>' : '') +
		        '</div>';
		    }
		},
		onChange(value) {
			window.province_id = value;
			let provinceList = [];
			Object.entries(this.options).map( e => {
				provinceList.push(e[1]);
			});
			provinceList.forEach( e => {
				//get ProvinceName to filter out province name at ajax return of $selectDistrict
				e.ProvinceID === value ? window.ProvinceName = value : '';
			});

			if( $selectDistrict && $selectDistrict[0].selectize !== undefined){
				let selectize = $selectDistrict[0].selectize;
				selectize.destroy();
				selectize.clear();
			};

			getDistrict( value );
			
		},
		onClear(){
			//console.log('getProvince_onClear');
			if( $selectDistrict ) {
				let selectize = $selectDistrict[0].selectize;
				selectize.destroy();
				selectize.clear();
			};

			if( $selectWard  && $selectWard[0].selectize !== undefined) {
				let selectize = $selectWard[0]?.selectize;
				selectize.destroy();
				selectize.clear();
			};
		}
	});

	let getDistrict =  function (province_id) {
		$selectDistrict = $('#select-district').selectize({
				plugins: ["remove_button"],
				options: [],
				maxItems: 1,
				preload: true,
				labelField: 'DistrictName',
				valueField: 'DistrictID',
				searchField: ['DistrictName'],
				sortField: 'DistrictName',
				closeAfterSelect: true,
				create: false,
				placeholder: 'Select your district...',
				load: function(query, callback) {
					if( ! province_id ) return;
					window.district_id = province_id;

					$.ajax({
						method : 'get',
						url : `https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=${province_id}` ,
						headers: {
				        	token:  '503b4b90-c9fb-11eb-bc42-967656021ee1',
				    	},
				        success: function(response) {
				        	let dist = response.data.filter( e => {
				        		
				        		return e.DistrictName !== ProvinceName;
				        	});

				        	callback(dist);
				        }
				    	
					});
				},
				onChange(value) {
					
					if( $selectWard && $selectWard[0].selectize !== undefined) {
						let selectize = $selectWard[0]?.selectize;
						selectize.destroy();
						selectize.clear();
					};
						
					getWard( value );
					
				},
				onClear(){
					//console.log('getDistrict_onClear');
					if( $selectWard && $selectWard[0].selectize !== undefined) {
						let selectize = $selectWard[0]?.selectize;
						selectize.destroy();
						selectize.clear();
					};
				}
				
			});
	}


	let getWard = function(district_id) {
		$selectWard = $('#select-ward').selectize({
			plugins: ["remove_button"],
			options: [],
			maxItems: 1,
			preload: true,
			labelField: 'WardName',
			valueField: 'WardCode',
			searchField: ['WardName'],
			sortField: 'WardName',
			closeAfterSelect: true,
			create: false,
			placeholder: 'Select your ward...',
			load: function(query, callback) {
				window.district_id = district_id;
				$.ajax({
					method : 'get',
					url : `https://dev-online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=${district_id}` ,
					headers: {
			        	token:  '503b4b90-c9fb-11eb-bc42-967656021ee1',
			    	},
			        success: function(response) {
			        	 callback(response.data);
			        	 let selectize = $selectWard[0].selectize;

			        }
			    	
				});
			},
			onChange(value) {
				let ward_id = value;
				if( province_id !== null && district_id !== null && ward_id !== null )
				{
					$.ajax({
						method : 'get',
						url : `https://dev-online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee?from_district_id=1463&service_type_id=2&to_district_id=${district_id}&weight=200&to_ward_code=${ward_id}` ,
						headers: {
				        	token:  '503b4b90-c9fb-11eb-bc42-967656021ee1',
				    	},
				        success: function(response) {
				        	let shippingFee = response.data.service_fee;
				        	let shippingFeeUSD = (shippingFee/23000).toFixed(2);
				        	document.getElementById('shipping').innerText = '$' + shippingFeeUSD;
				        	let grandTotal = +subTotal + +shippingFeeUSD
							$('#grandTotal span').text( `$ ${grandTotal.toFixed(2)}` );

							if(ward_id)
							{
								$.ajax({
									url: '{{route('app.saveShippingFee')}}',
									method: 'get',
									data: {shippingFee: shippingFeeUSD} ,
									sucess: function(response){},
									error: function(jqXHR, status, error) {
										alert('There is sth wrong!')
									}
								});
							}
							

				        }
				    	
					});
				}
			}

		});
	}

	window.onbeforeunload = closingCode;
	function closingCode(){
	    return confirm("Do you really want to close?") ; 
	}

});

/*
note
*/
//(1) this is solution 2 of the above.
</script>
<style>
.selectize-dropdown, .selectize-input, .selectize-input input{
	font-size: 17spx;
}
</style>
@endpush
