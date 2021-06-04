<template>
	<div>
		<!-- Shopping Item -->
		<a href="/cart" class="single-icon"><i class="ti-bag"></i> <span class="total-count">{{totalQuantity}}</span></a>
		<div class="shopping-item">
			<div class="dropdown-cart-header">
				<span> {{totalQuantity}} Items</span>
				<a href="#">View Cart</a>
			</div>
			<ul class="shopping-list">
				<li v-for="item in productsOnCart">
					<a href="#" class="remove" title="Remove this item"><i class="fa fa-remove"></i></a>
					<a class="cart-img" href="#"><img src="https://via.placeholder.com/70x70" alt="#"></a>
					<h4><a href="#">{{item.style}} | {{item.fullNumber}}</a></h4>
					<p class="quantity">{{item.quantity}}x - <span class="amount">${{item.price}}</span></p>
				</li>

			</ul>
			<div class="bottom">
				<div class="total">
					<span>Total</span>
					<span class="total-amount">${{totalAmount}}</span>
				</div>
				<a href="checkout.html" class="btn animate">Checkout</a>
			</div>
		</div>
	</div>
	<!--/ End Shopping Item -->
</template>

<script>
import { mapState } from 'vuex'
export default {
	props:[
		
	],
	data: function() {
		return {
			products: [],
			totalQuantity: 0,
			totalAmount:0
		}
	},
	computed: {
		...mapState([
			'productsOnCart',
			]),
	},
	methods: {
		arrangeProductsByDate(products){console.log(products)
			//reset the products, totalQuantity
			this.products = [];
			this.totalQuantity = 0;
			this.totalAmount =  0;

			products = _.orderBy(products, ['date'], ['desc']);
			products.forEach( ( item, index ) => {
				this.totalQuantity++;
				this.totalAmount += item.quantity * item.price;
				
				if( index === 0 || index === 1 ){
					this.products.push(item);
				} 
			});

		}
	},
	watch: {
		productsOnCart: {
			deep:true,
			handler: function(products) {
				this.arrangeProductsByDate(products);
				//console.log(products)
			}
		}
		
	}
}
</script>

<style scoped>

</style>
