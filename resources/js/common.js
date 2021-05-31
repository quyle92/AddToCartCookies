import { mapState } from 'vuex'


export default {
	data(){
		return {

		}
	},
	computed: {
		...mapState([
			'totalQuantity'  ,
			'selectedFullNumber'  ,
			'priceRange' , 
			'selectedPrice'  ,
			'productSet' , 
			'sizeColor',
			'sizeList',
			'colorList',
			'outOfStockColorAll',
			'outOfStockSizeAll'
			]),
	},
	methods: {
		    eitherSizeColor( result )
		   	{
	      		//get the key of item where its quantity is 0
	      		let priceRange = [];
	      		let totalQuantity = 0;

	      		//size: xem colors nào bị 0 quantity thì disabled nó
		        let outOfStockColor = [];
		        this.productSet.forEach( item => {
		        	item.size === this.sizeColor.size && item.quantity === 0
		        	? outOfStockColor.push(item.color) : '';
		        });
		        this.$store.state.outOfStockColor = [...outOfStockColor, ...this.outOfStockColorAll];
		        //console.log(this.$store.state.outOfStockColor)
				//color: xem sizes nào bị 0 quantity thì disabled nó
		        let outOfStockSize = [];
		        this.productSet.forEach( item => {
		        	item.color === this.sizeColor.color && item.quantity === 0
		        	? outOfStockSize.push(item.size) : '';
		        });
		        this.$store.state.outOfStockSize = [...outOfStockSize, ...this.outOfStockSizeAll];

	            //remove item where its quantity is < 0
	            result = result.filter( e =>  e.quantity > 0 ); 

	            result.forEach( (item) => {
	            	totalQuantity += item.quantity;
	            	priceRange.push( +item.price )
	            });
	            this.$store.commit('changeTotalQuantity', totalQuantity);

              	//set min price /max price
              	let minPrice = Math.round(_.min(priceRange));
                let maxPrice = Math.round(_.max(priceRange));
              	this.$store.commit('changePriceRange', {'minPrice': minPrice, 'maxPrice' : maxPrice});

              	vm.$emit('getPriceRangeOriginal', [...priceRange ] );

                //cho selectedPrice = '' để priceRange đc render trên template
                this.$store.commit('changeSelectedPrice', '');


            },
            bothSizeColor( ){  
				let selectedProduct = {};
				this.productSet.forEach( item => {
					item.size === this.sizeColor.size && item.color === this.sizeColor.color 
					? selectedProduct = item : '';
				});

				//console.log(this.totalQuantity)
				this.$store.commit('changeTotalQuantity', selectedProduct.quantity);
				this.$store.commit('changeSelectedFullNumber', selectedProduct.fullNumber);
				this.$store.commit('changeSelectedPrice', selectedProduct.price);

				//$on at ProductPage
				vm.$emit('getSelectedPriceOriginal', selectedProduct.price );
				
		        //size: xem colors nào bị 0 quantity thì disabled nó
		        let outOfStockColor = [];
		        this.productSet.forEach( item => {
		        	item.size === this.sizeColor.size && item.quantity === 0
		        	? outOfStockColor.push(item.color) : '';
		        });
		        this.$store.state.outOfStockColor = outOfStockColor;

		        //color: xem sizes nào bị 0 quantity thì disabled nó
		        let outOfStockSize = [];
		        this.productSet.forEach( item => {
		        	item.color === this.sizeColor.color && item.quantity === 0
		        	? outOfStockSize.push(item.size) : '';
		        });
		        this.$store.state.outOfStockSize = outOfStockSize;
	      	},
          	neitherSizeColor(){
        		 
        		let totalQuantity = 0;  
        		let priceRange = [];
        		let result = [];
        		
        		this.$store.state.outOfStockSize = [...this.outOfStockSizeAll];
        		this.$store.state.outOfStockColor = [...this.outOfStockColorAll];

                //remove item where its quantity is < 0
                result = this.productSet.filter( e =>  e.quantity > 0 ); 

                result.forEach( (item) => {
                	totalQuantity += item.quantity;
                	priceRange.push( +item.price )
                });
                this.$store.commit('changeTotalQuantity', totalQuantity);

                let minPrice = Math.round(_.min(priceRange));
                let maxPrice = Math.round(_.max(priceRange));
              	this.$store.commit('changePriceRange', {'minPrice': minPrice, 'maxPrice' : maxPrice});

                //$on at ProductPages
				// vm.$emit('getPriceRangeOriginal', [...priceRange ] );

                //cho selectedPrice = '' để priceRange đc render trên template
                this.$store.commit('changeSelectedPrice', '');
           	}

        },

}
