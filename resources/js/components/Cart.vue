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
										<p class="product-name"><a :href="'/product/' + item.style_id">{{item.style}} <small>| UUID: {{item.fullNumber}}</small></a></p>
										<div class="variation" 
										data-fullNumber="item.fullNumber"
										@click="showPopover(item, index)"
										>
											<span>Variation:{{item.size}} | {{item.color}} </span>
											<i class="fa fa-sort-up ml-1" v-if="item.isEdit"></i>
											<i class="fa fa-sort-down ml-1" v-else></i>

										</div>
										<popover 
										class="position-absolute bg-white" 
										style="z-index:999" 
										:keyIndex="index"
										v-if="item.isEdit"
										/>
									</td>
									<td class="price" data-title="Price" ref="mySecondLevelRefName">
										<span >${{item.price}} </span> 
		
					<!-- 					<span v-else>${{selectedPrice}} </span> </td> -->
									<td class="qty" data-title="Qty"><!-- Input Order -->
										<div class="input-group">
											<div class="button minus">
												<button type="button" class="btn btn-primary btn-number"  data-type="minus" data-field="quant[1]" 
												@click="minus(item)" >
													<i class="ti-minus"></i>
												</button>
											</div>
											<input type="text" name="quant[1]" class="input-number"  :data-min='min' 
											:data-max="max" 
											:value="item.quantity" 
											@change="checkInput( $event, item)"
											>
											<div class="button plus">
												<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]" 
												@click="add(item)">
													<i class="ti-plus"></i>
												</button>
											</div>
										</div>
										<small id="passwordHelpBlock" class="form-text text-danger text-center" >
                          Only {{ item.maxQuantity}} {{item.maxQuantity > 1 ? 'items' : 'item'}} left!
                        </small>
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
											<a href="#" class="btn btn-warning">Checkout</a>
											<a href="#" class="btn btn-warning">Continue shopping</a>
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
		 		show:false,
		 		count: 0,
		 		max: 10,
		 		min:1,
		 		selectedItem: {},
		 		styleSet:[],
		 		oldQuantity:''

 		}
  },
  computed: {
  	...mapState([
  		'totalQuantity'  ,
			'selectedFullNumber'  ,
			'priceRange' , 
			'selectedPrice'  ,
			'selectedStyleSet' ,
			'productsOnCart',
			'maxQuantityArr'

  		]),
  	totalQty(){
  		let result = 0;
  		if(! this.productsOnCart) return;

  		this.productsOnCart.forEach( (item) => {
  			 result += item.quantity
  		})
  		return result;
  	},
  	totalAmount(){
  		let result = 0;
  		if(! this.productsOnCart) return;

  		this.productsOnCart.forEach( item => {
  			let subTotal = item.quantity * item.price;
  			result += subTotal;
  		});

  		return result;
  	}

  },
  methods:{
  	onClose(){

  	},
  	showPopover(item, index)
  	{	
  		//hide popover  nếu click lại lần 2
  		let selectedProduct= _.find(this.productsOnCart, {'isEdit': true});//console.log(this.productsOnCart[0], selectedProduct)
  		if( _.isEqual(selectedProduct, item) ){
  			item.isEdit = false;
  			//$on: popover.vue
  			vm.$emit('cancel')
  			return;
  		}
  		
  		//hide popover cũ rồi mới show popover mới lên
  		this.productsOnCart.forEach( e => {
  				e.isEdit === true ? e.isEdit = false : '';
  			
  		});
  		
  		this.$store.state.selectedProduct  = item;
  		
  		this.$store.state.sizeColor = {
  			size: item.size,
  			color: item.color
  		};

  		let style_id = item.style_id;
  		if( this.styleSet.length > 0  )
  		{		
  				//check if selectedStyleSet is in selectedStyleSet, if not call to server
	  			for (var i = 0; i < this.styleSet.length; i++) {
	  				if( this.styleSet[i][0].style_id === item.style_id ){
	  						this.$store.state.selectedStyleSet = this.styleSet[i];
	  						this.bothSizeColor();
	  						item.isEdit = true;//(2)
	  						return;
	  				}
	  			}

	  			axios.get(`/getSelectedStyleSet?styleID=${style_id}`)
						.then( ( response ) => {
								this.styleSet.push(response.data);
								this.$store.state.selectedStyleSet = response.data;
								this.bothSizeColor();
								item.isEdit = true;//(2)
						})
						.catch(function (error) {
							console.log(error);
						})

	  			
  			 
			
  		}
  		else
  		{
  				axios.get(`/getSelectedStyleSet?styleID=${style_id}`)
						.then( ( response ) => {
								this.styleSet.push(response.data);

								let selectedStyleSet = response.data;
								
					  		this.$store.state.selectedStyleSet = selectedStyleSet;
					  		this.bothSizeColor();
								this.productsOnCart[index].isEdit = true;//(2)
					  		
						})
						.catch(function (error) {
							console.log(error);
						})
  		}
  		

  		

  	},
  	removeItem(item, index){
  		let storageProducts = JSON.parse(localStorage.getItem('products'));
  		let products = storageProducts.filter( e => {
  				return e.fullNumber !== item.fullNumber || e.fullNumber === item.fullNumber && e.size !== item.size && e.color !== item.color
  				
  		});

  		localStorage.setItem('products', JSON.stringify(products));

  		this.productsOnCart.splice( index, 1 );
  	},
  	add(item){
			this.$store.commit('SET_SELECTED_PRODUCT', item);

  		if(item.quantity < item.maxQuantity ){
          item.quantity++;
          this.updateLocalStorage();
        }
      else
      {
        alert('stop: max reache')
      }
        
  	},
  	minus(item){
  		if(item.quantity > this.min ){
          item.quantity--;
        }
        else
        {
          alert('stop: min reache')
        }
        
  	},
  	checkInput(e, item){
				let inputVal = e.target.value
        if(  ! isNaN(inputVal) && inputVal > 1 && inputVal <= item.maxQuantity ){
          item.quantity = +inputVal;
          this.selectedProduct = item;
          this.updateLocalStorage();
        }
        else{
  				alert('stop: invalid');
        	this.$forceUpdate()
        }
        

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
      		//console.log('watch',newObj);
  		}
    }
  },
  created(){
  		let fullNumberArr = [];
  		if( ! localStorage.getItem('products') ) return;

  		this.$store.state.sizeList = sizeList;
			this.$store.state.colorList = colorList;

  		let products =  JSON.parse( localStorage.getItem('products') );
  		products.forEach( item =>  {
  				Vue.set(item, 'isEdit', false);
  				fullNumberArr.push(item.fullNumber)
  		});

			axios.get(`/getMaxQuantityForEachItem?params=${fullNumberArr}` )
					.then( ( response ) => {
							let maxQuantityArr = response.data.maxQuantityArr;

							products = _.orderBy(products, ['fullNumber'], ['asc']);
							maxQuantityArr = _.orderBy(maxQuantityArr, ['fullNumber'], ['asc']);

							for (var i = 0; i < maxQuantityArr.length; i++) {
								products[i].maxQuantity = maxQuantityArr[i].quantity;
							}

							products = _.orderBy(products, ['style_id', 'date'], ['asc', 'asc']);
							this.$store.state.productsOnCart =  products;

					})	
					.catch(function (error) {
						console.log(error);
					});

			
			
		


			



  },
  mounted(){
  	

  }
}
</script>

<style scoped>
.variation{
	cursor: pointer;
	width: max-content;
}
</style>

<!--
Note:
//(1):  Data will not update with state changes, so you need to have the state mapped in the selectedFullNumber computed to watch for changes in state, or create a watcher. Ref: https://stackoverflow.com/a/49355300/11297747
, https://stackoverflow.com/questions/52353370/vuejs-cant-assign-value-from-mapstate-to-data-property-after-reloading-the-pa

//(2): must put here so that disabled class in popover.vue will be attached to the button there first
must put here so that disabled class in popover.vue will be attached to the button there first
must put here so that disabled class in popover.vue will be attached to the button there first