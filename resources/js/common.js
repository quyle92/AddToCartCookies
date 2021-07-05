import { mapState } from 'vuex'


export default {
	data(){
		return {

		}
	},
	computed: {
		...mapState([
			'selectedFullNumber',
			'priceRange' , 
			'productsOnCart',
			'selectedPrice'  ,
			'selectedStyleSet' , 
			'sizeColor',
			'sizeList',
			'colorList',
			'outOfStockColorAll',
			'outOfStockSizeAll',
			'selectedProduct',
			'lastSelectedProduct',
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
							//TH này là cho productPage
							this.$store.commit('SET_SELECTED_PRODUCT', item);
							this.$store.commit('SET_TOTAL_QUANTITY', this.selectedProduct.quantity);
							this.$store.commit('SET_SELECTED_PRICE', this.selectedProduct.price);
						}
						else
						{	
							//TH này là cho cartPage
							this.selectedProduct.size = item.size;
							this.selectedProduct.color = item.color;
							this.selectedProduct.price = item.price;
							this.selectedProduct.fullNumber = item.fullNumber;
							this.selectedProduct.maxQuantity = item.quantity;
						}
						

					}
					
				});

				let productsOnCart =  this.productsOnCart;

				let selectedSizeFromOtherProducts = [];
				productsOnCart.forEach( e => {
					if(e.fullNumber !==  this.selectedProduct.fullNumber)
						selectedSizeFromOtherProducts.push({'style_id': e.style_id, 'size': e.size});
				});

				let selectedColorFromOtherProducts = [];
				productsOnCart.forEach( e => {
					if(e.fullNumber !==  this.selectedProduct.fullNumber)
						selectedColorFromOtherProducts.push({'style_id': e.style_id, 'color': e.color});
				});

				//$on at ProductPage
				vm.$emit('getSelectedPriceOriginal', this.selectedProduct.price );

		        //size: xem colors nào bị 0 quantity thì disabled nó
		        let outOfStockColor = [];
		        this.selectedStyleSet.forEach( item => {
		        	item.size === this.sizeColor.size && item.quantity === 0 
		        	? outOfStockColor.push(item.color) : '';
		        });
		        //cart page: disable color that already been selected from other items (with same style_id) in cart
		        if( this.selectedProduct.hasOwnProperty('isEdit') ) {
			        for (var i = 0; i < selectedSizeFromOtherProducts.length; i++) {
			        	if ( selectedSizeFromOtherProducts[i].size === this.sizeColor.size 
			        		&& selectedSizeFromOtherProducts[i].style_id === this.selectedProduct.style_id) {
			        		outOfStockColor.push(selectedColorFromOtherProducts[i].color)
			        	}
			        }
			    }

		        this.$store.state.outOfStockColor = outOfStockColor;

		        //color: xem sizes nào bị 0 quantity thì disabled nó
		        let outOfStockSize = [];
		        this.selectedStyleSet.forEach( item => {
		        	item.color === this.sizeColor.color && item.quantity === 0 
		        	? outOfStockSize.push(item.size) : '';
		        });
		        //cart page: disable color that already been selected from other items (with same style_id) in cart
		        if( this.selectedProduct.hasOwnProperty('isEdit') ) {
			        for (var i = 0; i < selectedColorFromOtherProducts.length; i++) {
			        	if ( selectedColorFromOtherProducts[i].color === this.sizeColor.color
			        	&& selectedColorFromOtherProducts[i].style_id === this.selectedProduct.style_id )
			        		outOfStockSize.push(selectedSizeFromOtherProducts[i].size)
			        }
			    }

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

                //cho selectedPrice = '' để priceRange đc render trên template
                this.$store.commit('SET_SELECTED_PRICE', '');
           	},
           	updateLocalStorage(){

	        	if( ! localStorage.getItem('products') ) return;

	        	let productsOnStorage =  this.productsOnCart;

	        	//replace existing product by new one
	        	if( ! this.$Helper.isObjEmpty( this.lastSelectedProduct ) ) {

			  		JSON.parse( localStorage.getItem('products') ).forEach( (el, idx) => {
			  			if(el.fullNumber === this.lastSelectedProduct.fullNumber)
			  				JSON.parse( localStorage.getItem('products') )[idx] = this.selectedProduct
			  		})

	        	}

	        	//updade existing product quantity
	        	if( this.$Helper.isObjEmpty( this.lastSelectedProduct ) ) {

	        		productsOnStorage.map( e => {
	        			e.fullNumber === this.selectedProduct.fullNumber ? 
	        							e.quantity = this.selectedProduct.quantity 
	        							: '';
	        		});

	        	}

	        	this.$store.state.productsOnCart = productsOnStorage;
	        	localStorage.setItem('products', JSON.stringify( productsOnStorage ) ) ;
	  		
        }

    }


}
