<template>
	<div  >
		<div class="sinlge-bar shopping popoverVariation" ref="insidePopover">
			<form>
				<div>
					<span class="d-block">Size</span>
					<div class="buttonList">
						<button type="button" class="btn btn-default product-variation"  
						v-for="(item, index) in hightlight" :key="index"  
						@click="select( Object.keys(item)[0] , $event.target)" 
						:class="Object.values(item)[0]"
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
export default {
  props:[ 'selectedItem' ],
  data: function() {
    return {
    	selectedSize:'',
 		sizeList:['S', 'M', 'L']
 	  }
  },
  methods:{
  	cancel(){
  		$('.popoverVariation').on( "click", ".cancel", function( e ) {
  			console.log('popoverVariation')
  			$(this).parentsUntil(".product-des").parent().find('.position-absolute').addClass('d-none');
  			$(this).parentsUntil(".product-des").parent().find('i.fa').replaceWith(  '<i class="fa fa-sort-up ml-1"></i>' );
  		});
  	},
  	confirm(){
  		this.$store.state.selectedSize = this.selectedSize;
  		$('.popoverVariation').on( "click", ".confirm", function() {
  			$(this).prev().click();//(1)
  			$(this).prev().trigger( "click" );//(1)
  		});
  	},
  	select(size, target){
  		this.selectedSize = size;
  		
  		let parent = $(target).parent();
  		parent.children('button').each( function( i, e ) {
	       $(e).removeClass('btn-warning');
	    });
  		$(target).addClass('btn-warning');

  	}
  },
  computed:{
  	hightlight(){
  		let sizeList = { ...this.sizeList };
  		for (let item in sizeList) {//console.log( sizeList[item] )
  			if( sizeList[item] === this.selectedSize){
  				  let obj = {};
			      obj[this.selectedSize] = 'btn-warning'
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
  		//console.log(sizeList);
  	}
  },
  created(){
  	this.selectedSize = this.selectedItem.size;
  	//console.log(this)
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

.confirmation-area {

}

</style>

<!-- notes
(1): phải cho click trước rồi trigger sau mới đc. Ref: https://api.jquery.com/trigger/#trigger-eventType-extraParameters (example đầu tiên)
 -->
