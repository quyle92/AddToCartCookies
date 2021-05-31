<template>
	<div  >
		<div class="sinlge-bar shopping popoverVariation" :id="itemInfo.fullNumber" v-if="itemInfo.isEdit">
			<form>
				<div>
					<span class="d-block">Size</span>
					<div class="buttonList">
						<button type="button" class="btn btn-default product-variation size"  
						v-for="(item, index) in hightlightSize" :key="index"  
						@click="selectSize( Object.keys(item)[0] , $event.target)" 
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
						@click="selectColor( Object.keys(item)[0] , $event.target)" 
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
  props:['itemInfo'],
  data: function() {
    return {
 		// sizeList: ['XS','S', 'M', 'L', 'XL', 'XXL'],
 		// colorList: ['black','white', 'gray', 'maroon', 'purple', 'navy', 'teal'],
 		lastSelectedSize:'',
 		lastSelectedColor:'',
 		countSizeSelect: 0, 
 		countColorSelect: 0, 
 	  }
  },
    computed:{
  	...mapState([
  		'selectedProduct'  ,
  		'outOfStockSize',
  		'outOfStockColor',
  		'sizeColor',
  		'sizeList',
  		'colorList'
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
			      //console.log( colorList[item] )
  			}
  			else{
  				let obj = {};
  				obj[colorList[item] ] = ''
			    colorList[item] = obj;
			   // console.log( 'a',colorList[item] )
  			}
  		};
  		return colorList;
 
  	}
  },
  methods:{
  	cancel(){

  			if( this.lastSelectedSize )
  			{
  					this.selectedProduct.size = this.sizeColor.size = this.lastSelectedSize;
  			}

  			if( this.lastSelectedColor )
  			{
  					this.selectedProduct.color = this.sizeColor.color = this.lastSelectedColor;
  			}

  		this.lastSelectedSize = this.lastSelectedColor = '';
  		this.countSizeSelect = this.countColorSelect = 0 ;


  		this.itemInfo.isEdit = false;

  		// $('.popoverVariation').on( "click", ".cancel", function( e ) {

  		// 	$(this).parentsUntil(".product-des").parent().find('.position-absolute').addClass('d-none');
  		// 	$(this).parentsUntil(".product-des").parent().find('i.fa').replaceWith(  '<i class="fa fa-sort-up ml-1"></i>' );
  		// });
  	},
  	confirm(){

  		this.lastSelectedSize = this.lastSelectedColor = '';
  		this.countSizeSelect = this.countColorSelect = 0 ;
  		
  		this.itemInfo.isEdit = false;


  		// this.$store.state.selectedSize = this.selectedSize;
  		// $('.popoverVariation').on( "click", ".confirm", function() {
  		// 	$(this).prev().click();//(1)
  		// 	$(this).prev().trigger( "click" );//(1)
  		// });
  	},
  	selectSize(size, target)
  	{	
  		if( this.outOfStockSize.includes(size) ) return;
  		
  		if( this.countSizeSelect === 0 ) {
  			this.lastSelectedSize = this.selectedProduct.size;
  		}
  		this.countSizeSelect++;

  		this.selectedProduct.size = size;
  		this.sizeColor.size = size;

  		this.bothSizeColor()
  	},
  	selectColor(color, target)
  	{
  		if( this.outOfStockColor.includes(color) ) return;

  		if( this.countColorSelect === 0 ) {
  			this.lastSelectedColor = this.selectedProduct.color;
  		}
  		this.countColorSelect++
  		this.selectedProduct.color = color;
  		this.sizeColor.color = color;

  		this.bothSizeColor()
  	}
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
		background-color: transparent;
		box-shadow: 0 0 0 0.2rem rgb(52 144 220 / 25%);
}

</style>

<!-- notes
(1): phải cho click trước rồi trigger sau mới đc. Ref: https://api.jquery.com/trigger/#trigger-eventType-extraParameters (example đầu tiên)
 -->
