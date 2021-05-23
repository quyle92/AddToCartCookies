<template>
	<div>
		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="/home">Home<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="#">Cart</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->
				
		<!-- Shopping Cart -->
		<div class="shopping-cart section">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<!-- Shopping Summery -->
						<table class="table shopping-summery">
							<thead>
								<tr class="main-hading">
									<th>PRODUCT</th>
									<th>NAME</th>
									<th class="text-center">UNIT PRICE</th>
									<th class="text-center">QUANTITY</th>
									<th class="text-center">TOTAL</th> 
									<th class="text-center"><i class="ti-trash remove-icon" ></i></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(item, index) in productsOnCart" :key='index'  >
									<td class="image" data-title="No"><img src="https://via.placeholder.com/100x100" alt="#"></td>
									<td class="product-des" data-title="Description" 					
									>
										<p class="product-name"><a href="#">{{item.productName}}</a></p>
										<div class="variation" 
										:id="item.fullNumber"
										@click="showPopover(item.fullNumber)"
										>
											<span>Variation:{{item.size}}</span><i class="fa fa-sort-up ml-1"></i>

										</div>
										<popover 
										
										class="position-absolute bg-white d-none" 
										style="z-index:999" 
										:selectedItem="item"
										/>
									</td>
									<td class="price" data-title="Price" ref="mySecondLevelRefName"><span>${{item.price}} </span></td>
									<td class="qty" data-title="Qty"><!-- Input Order -->
										<div class="input-group">
											<div class="button minus">
												<button type="button" class="btn btn-primary btn-number"  data-type="minus" data-field="quant[1]" 
												@click="substract(item)" >
													<i class="ti-minus"></i>
												</button>
											</div>
											<input type="text" name="quant[1]" class="input-number"  :data-min='min' 
											:data-max="max" 
											:value="item.quantity" 
											@change="checkInput($event, item)"
											:id="item.fullNumber"
											
											>
											<div class="button plus">
												<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]" 
												@click="add(item)">
													<i class="ti-plus"></i>
												</button>
											</div>
										</div>
										<!--/ End Input Order -->
									</td>
									<td class="total-amount" data-title="Total"><span>${{item.quantity * item.price}}</span></td>
									<td class="action" data-title="Remove"><a href="#" @click.prevent="removeItem(item, index)"><i class="ti-trash remove-icon"></i></a></td>
								</tr>
								<tr v-if="productsOnCart.length < 1">
									<td></td>
									<td></td>
									<td><h3 class="text-center">Your cart is empty</h3></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
						<!--/ End Shopping Summery -->
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<!-- Total Amount -->
						<div class="total-amount">
							<div class="row">
								<div class="col-lg-8 col-md-5 col-12">
									<div class="left">
										<div class="coupon">
											<form action="#" target="_blank">
												<input name="Coupon" placeholder="Enter Your Coupon">
												<button class="btn">Apply</button>
											</form>
										</div>
										<div class="checkbox">
											<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox"> Shipping (+10$)</label>
										</div>
									</div>
								</div>
								<div class="col-lg-4 col-md-7 col-12">
									<div class="right">
										<ul>
											<li>Total Quantity<span>{{totalQty}}</span></li>
											<li class="last">You Pay<span>${{totalAmount}}</span></li>
										</ul>
										<div class="button5">
											<a href="#" class="btn">Checkout</a>
											<a href="#" class="btn">Continue shopping</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--/ End Total Amount -->
					</div>
				</div>
			</div>
		</div>
		<!--/ End Shopping Cart -->
		</div>
</template>

<script>
import { mapState } from 'vuex'
import { mapGetters } from 'vuex'
//import clickOutside from '../directive';

export default {
  props: [
     'products'
   ],
  data: function() {
    return {
 		productsOnCart:[],
 		//popover: false,
 		show:false,
 		count: 0,
 		max: 10,
 		min:1
 	  }
  },
  methods:{
  	onClose(){

  	},
  	showPopover(fullNumber)
  	{	
  		//hide popover cũ rồi mới show popover mới lên
  		$('.position-absolute').each( function( i, e ) {
  			$(e).addClass('d-none');
  		})


  		this.$store.state.selectedFullNumber = fullNumber;

  	},
  	removeItem(item, index){
  		this.productsOnCart.splice( index, 1 )	;
  	},
  	add(item){
  		if(item.quantity < this.max ){
          item.quantity++;
        }
        else
        {
          alert('stop: max reache')
        }
        console.log(item.quantity)
  	},
  	substract(item){
  		if(item.quantity > this.min ){
          item.quantity--;
        }
        else
        {
          alert('stop: min reache')
        }
        console.log(item.quantity)
  	},
  	checkInput(e, item){

        if( isNaN(e.data) || e.data < min || e.data > max){
          alert('stop: invalid')
        }
  	}
  },
  computed: {
  	...mapState([
  		'selectedFullNumber'	
  		]),
  	...mapGetters([
      'getSelectedSize'
      ]),
  	totalQty(){
  		let result = 0;
  		this.productsOnCart.forEach( (item) => {
  			 result += item.quantity
  		})
  		return result;
  	},
  	totalAmount(){
  		let result = 0;

  		this.productsOnCart.forEach( item => {
  			let subTotal = item.quantity * item.price;
  			result += subTotal;
  		});

  		return result;
  	}

  },
  watch:{
  	getSelectedSize(selectedSize) {
  		this.productsOnCart.map( (item) => {
  			if( item.fullNumber === this.selectedFullNumber ){
  				item.size = selectedSize;
  			}
  		})
  	},
  	productsOnCart: {
  		deep: true,
  		handler: function (newObj, oldObj) {
      		//console.log('hi',newObj);
  		}
    }
  },
  created(){
	  	Object.entries( this.products).forEach( (item) => {
	  		let [, value] = item;
			this.productsOnCart.push( JSON.parse(value) );
	  	});

  },
  mounted(){
  	

  }
}
</script>

<style scoped>
.variation{
	cursor: pointer;
}
</style>

<!--
Note:
//(1):  Data will not update with state changes, so you need to have the state mapped in the selectedFullNumber computed to watch for changes in state, or create a watcher. Ref: https://stackoverflow.com/a/49355300/11297747
, https://stackoverflow.com/questions/52353370/vuejs-cant-assign-value-from-mapstate-to-data-property-after-reloading-the-pa