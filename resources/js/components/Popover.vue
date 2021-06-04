<template>
	<div  >
		<div class="sinlge-bar shopping popoverVariation"  >
			<form>
				<div>
					<span class="d-block">Size</span>
					<div class="buttonList">
						<button type="button" class="btn btn-default product-variation size"  
						v-for="(item, index) in hightlightSize" :key="index"  
						@click="selectSize( Object.keys(item)[0] )" 
						:class="[
						Object.values(item)[0] , 
						{
							'disabled': outOfStockSize.includes( Object.keys(item)[0] )
						}
						]"
						>
							{{ Object.keys(item)[0]   }}
						</button>
					</div>
					<span class="d-block">Color</span>
					<div class="colorList">
						<button type="button" class="btn btn-default product-variation color"  
						v-for="(item, index) in hightlightColor" :key="index"  
						@click="selectColor( Object.keys(item)[0] )" 
						:class="[
						Object.values(item)[0] , 
						{
							'disabled': outOfStockColor.includes( Object.keys(item)[0] )
						}
						]"
						>
							{{ Object.keys(item)[0]  }}
						</button>
					</div>
				</div>
				<div class="mt-3">
					<button type="button" class="btn btn-default cancel" @click='cancel'>Cancel</button>
					<button type="button" class="btn btn-danger confirm" @click="confirm">OK</button>
				</div>
			</form>

			
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex'
import { mapGetters } from 'vuex'
export default {
  props:[ 'keyIndex'],
  data: function() {
    return {
 		count: 0,
 		//lastSelectedProduct: {}
 	  }
  },
    computed:{
  	...mapState([
  		'selectedProduct'  ,
  		'outOfStockSize',
  		'outOfStockColor',
  		'sizeColor',
  		'sizeList',
  		'colorList',
  		'selectedPrice',
  		'selectedStyle',
  		'maxQuantityArr',
  		'lastSelectedProduct'
  		]),
  	hightlightSize(){
  		let sizeList = { ...this.sizeList };
  		for (let item in sizeList) {
  			if( sizeList[item] === this.selectedProduct.size ){
  				  let obj = {};
			      obj[sizeList[item]] = 'btn-warning'
			      sizeList[item] = obj;
			      //console.log( sizeList[item] )
  			}
  			else{
  				let obj = {};
  				obj[sizeList[item] ] = ''
			    sizeList[item] = obj;
			   // console.log( 'a',sizeList[item] )
  			}
  		};
  		return sizeList;
  		
  	},
  	hightlightColor(){
  		let colorList = { ...this.colorList };
  		for (let item in colorList) {
  			if( colorList[item] === this.selectedProduct.color ){
  				  let obj = {};
			      obj[colorList[item]] = 'btn-warning'
			      colorList[item] = obj;
  			}
  			else{
  				let obj = {};
  				obj[colorList[item] ] = ''
			    colorList[item] = obj;
  			}
  		};
  		return colorList;
 
  	}
  },
  methods:{
  	selectSize(size)
  	{		
  		if( this.outOfStockSize.includes(size) ) return;

  		if( this.count === 0 ) {
  			this.$store.commit('SET_LAST_SELECTED_PRODUCT', {...this.selectedProduct});
  			
//console.log( this.lastSelectedProduct, this.lastSelectedProduct.size) (2)
  		}
  		this.count++;


  		this.sizeColor.size = size;
  		
  		this.bothSizeColor();
  		this.selectedProduct.date = new Date();
  		
  	},
  	selectColor(color)
  	{
	  		if( this.outOfStockColor.includes(color) ) return;

	  		if( this.count === 0 ) {
	  			// this.lastSelectedProduct = {...this.selectedProduct};
	  			this.$store.commit('SET_LAST_SELECTED_PRODUCT', {...this.selectedProduct});
	  		}
	  		this.count++;

	  		this.sizeColor.color = color;

	  		this.bothSizeColor();
	  		this.selectedProduct.date = new Date();

  	},
  	cancel(){
  			this.selectedProduct.isEdit = false;
  			if( this.selectedProduct.quantity > this.lastSelectedProduct.maxQuantity ) {
  			 this.selectedProduct.quantity = this.lastSelectedProduct.maxQuantity;
  			}

  			if( ! _.isEmpty(this.lastSelectedProduct)   )
  			{		
  					this.selectedProduct.size = this.lastSelectedProduct.size;
  					this.selectedProduct.color = this.lastSelectedProduct.color;
  					this.selectedProduct.price = this.lastSelectedProduct.price;
  					this.selectedProduct.fullNumber = this.lastSelectedProduct.fullNumber;
  					this.selectedProduct.maxQuantity = this.lastSelectedProduct.maxQuantity;

  			}


	  		this.$store.commit('SET_LAST_SELECTED_PRODUCT', {} );
	  		this.count = 0 ;


	  		

	  		// $('.popoverVariation').on( "click", ".cancel", function( e ) {

	  		// 	$(this).parentsUntil(".product-des").parent().find('.position-absolute').addClass('d-none');
	  		// 	$(this).parentsUntil(".product-des").parent().find('i.fa').replaceWith(  '<i class="fa fa-sort-up ml-1"></i>' );
	  		// });
  	},
  	confirm(){

  		this.selectedProduct.isEdit = false;
  		if( this.selectedProduct.quantity > this.selectedProduct.maxQuantity ) {
  			 this.selectedProduct.quantity = this.selectedProduct.maxQuantity;
  		}

  		this.updateLocalStorage();

  		this.$store.commit('SET_LAST_SELECTED_PRODUCT', {} );
  		this.count = 0 ;
  		// this.$store.state.selectedSize = this.selectedSize;
  		// $('.popoverVariation').on( "click", ".confirm", function() {
  		// 	$(this).prev().click();//(1)
  		// 	$(this).prev().trigger( "click" );//(1)
  		// });
  		
  	},
  	
  },

  created(){
  	
  	

  	vm.$on('cancel', () => {
  		this.cancel()
  	})

  }
}
</script>

<style scoped>
.sinlge-bar.shopping {
    border: 1px solid rgba(0,0,0,.09);
    box-shadow: 0 5px 10px 0 rgb(0 0 0 / 9%);
    padding: 10px;
}

.product-variation {
	border-radius: 2px;
	border: 1px solid rgba(0,0,0,.09);
}

.disabled{
  color: rgba(0,0,0,.26);
  cursor: not-allowed;
}

.btn.disabled:hover {
	box-shadow: none;
}

.btn.disabled:focus{
	box-shadow: none;
	background-color: transparent;
}


.btn.disabled:hover{
	background: transparent;
	box-shadow: transparent
}

.btn:hover{
		background-color: #ffed4a;
		box-shadow: 0 0 0 0.2rem rgb(52 144 220 / 25%);
}



</style>

<!-- notes
(1): phải cho click trước rồi trigger sau mới đc. Ref: https://api.jquery.com/trigger/#trigger-eventType-extraParameters (example đầu tiên)
(2):nếu this.lastSelectedProduct = this.selectedProduct: size trong this.lastSelectedProduct khác với this.lastSelectedProduct.size vì this.lastSelectedProduct đã đc updated rồi, còn this.lastSelectedProduct.size là giá trị ban đầu.
 -->
