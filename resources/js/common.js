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
			'selectedStyleSet' , 
			'sizeColor',
			'sizeList',
			'colorList',
			'outOfStockColorAll',
			'outOfStockSizeAll',
			'productsOnCart',
			'selectedProduct',
			'lastSelectedProduct'
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
		        this.selectedStyleSet.forEach( item => {
		        	item.size === this.sizeColor.size && item.quantity === 0
		        	? outOfStockColor.push(item.color) : '';
		        });
		        this.$store.state.outOfStockColor = [...outOfStockColor, ...this.outOfStockColorAll];

				//color: xem sizes nào bị 0 quantity thì disabled nó
		        let outOfStockSize = [];
		        this.selectedStyleSet.forEach( item => {
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
	            this.$store.commit('SET_TOTAL_QUANTITY', totalQuantity);

              	//set min price /max price
              	let minPrice = Math.round(_.min(priceRange));
                let maxPrice = Math.round(_.max(priceRange));
              	this.$store.commit('SET_PRICE_RANGE', {'minPrice': minPrice, 'maxPrice' : maxPrice});

              	vm.$emit('getPriceRangeOriginal', [...priceRange ] );

                //cho selectedPrice = '' để priceRange đc render trên template
                this.$store.commit('SET_SELECTED_PRICE', '');


            },
            bothSizeColor( ){  
            	
				this.selectedStyleSet.forEach( item => {
					if (item.size === this.sizeColor.size && item.color === this.sizeColor.color )
					{	
						if( this.$Helper.isObjEmpty(this.selectedProduct) )
						{
							// this.selectedProduct = item;
							this.$store.commit('SET_SELECTED_PRODUCT', item);
							
						}
						else
						{
							this.selectedProduct.size = item.size;
							this.selectedProduct.color = item.color;
							this.selectedProduct.price = item.price;
							this.selectedProduct.fullNumber = item.fullNumber;
							this.selectedProduct.maxQuantity = item.quantity;
							

						}
						

					}
					
				});

				this.$store.commit('SET_TOTAL_QUANTITY', this.selectedProduct.quantity);
				this.$store.commit('SET_SELECTED_PRICE', this.selectedProduct.price);

				//$on at ProductPage
				vm.$emit('getSelectedPriceOriginal', this.selectedProduct.price );

		        //size: xem colors nào bị 0 quantity thì disabled nó
		        let outOfStockColor = [];
		        this.selectedStyleSet.forEach( item => {
		        	item.size === this.sizeColor.size && item.quantity === 0
		        	? outOfStockColor.push(item.color) : '';
		        });
		        this.$store.state.outOfStockColor = outOfStockColor;

		        //color: xem sizes nào bị 0 quantity thì disabled nó
		        let outOfStockSize = [];
		        this.selectedStyleSet.forEach( item => {
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
                result = this.selectedStyleSet.filter( e =>  e.quantity > 0 ); 

                result.forEach( (item) => {
                	totalQuantity += item.quantity;
                	priceRange.push( +item.price )
                });
                this.$store.commit('SET_TOTAL_QUANTITY', totalQuantity);

                let minPrice = Math.round(_.min(priceRange));
                let maxPrice = Math.round(_.max(priceRange));
              	this.$store.commit('SET_PRICE_RANGE', {'minPrice': minPrice, 'maxPrice' : maxPrice});

                //$on at ProductPages
				// vm.$emit('getPriceRangeOriginal', [...priceRange ] );

                //cho selectedPrice = '' để priceRange đc render trên template
                this.$store.commit('SET_SELECTED_PRICE', '');
           	},
           	updateLocalStorage( ){

	        	if( ! localStorage.getItem('products') ) return;

	        	let productsOnStorage =  JSON.parse( localStorage.getItem('products') );

	        	//replace existing product by new one
	        	if( ! this.$Helper.isObjEmpty( this.lastSelectedProduct ) ) {

			  		productsOnStorage = productsOnStorage.filter( e => {
			  			return e.fullNumber !== this.lastSelectedProduct.fullNumber;
			  		});

			  		productsOnStorage.push( this.selectedProduct );

	        	}
	        	
	        	//updade existing product quantity
	        	if( this.$Helper.isObjEmpty( this.lastSelectedProduct ) ) {

	        		productsOnStorage.map( e => {
	        			e.fullNumber === this.selectedProduct.fullNumber ? 
	        							e.quantity = this.selectedProduct.quantity 
	        							: '';
	        		});

	        	}

	        	localStorage.setItem('products', JSON.stringify( productsOnStorage ) ) ;
	  		
        }

    }


}
