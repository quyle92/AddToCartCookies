import Vue from 'vue';
import Vuex from 'vuex';

//helper.js
// import Helper from './helper';
// Vuex.prototype.$Helper = new Helper();

Vue.use(Vuex);

export default  new Vuex.Store({
	state:
	{	
		selectedProduct: window.product,
		priceRange: '',
		selectedFullNumber:'',
		//selectedSize:'',
		totalQuantity: window.totalQuantity,
		productSet: [],
		selectedPrice:'',
		outOfStockSize: '',
		outOfStockColor: '',
		outOfStockSizeAll: '',
		outOfStockColorAll: '',
		sizeColor:{
          size: '',
          color: ''
        },
        sizeList: [],
        colorList:[],


	},
	getters:
	{


	},
	mutations:
	{
		getProductSet( state, payload )
		{
			let productSet = payload.data;
			let sizeList = [];

			this.state.sizeList.forEach(size => {
			  sizeList.push(Object.values(size)[0])
			})
			let colorList = [];
			this.state.colorList.forEach(color => {
			  colorList.push(Object.values(color)[0])
			})

			this.state.outOfStockSize = getOutOfStockVariation(sizeList, productSet);
			this.state.outOfStockSizeAll = getOutOfStockVariation(sizeList, productSet);

			this.state.outOfStockColor = getOutOfStockVariation(colorList, productSet);
			this.state.outOfStockColorAll = getOutOfStockVariation(colorList, productSet);

			this.state.productSet = productSet;
			
		},

		changeTotalQuantity( state, payload ){

			this.state.totalQuantity = payload;
		},

		changePriceRange( state, payload ){
			this.state.priceRange['min(price)'] = payload.minPrice;
			this.state.priceRange['max(price)'] = payload.maxPrice;
			console.log(this.state.priceRange)
		},

		changeSelectedPrice( state, payload ){
			this.state.selectedPrice = payload;
		},

		changeSelectedFullNumber( state, payload ){
			this.state.selectedFullNumber = payload;
		}
	},
	actions:
	{
		getProductSetAction({ commit }, payload){
			axios.get(`/getPriceQuantity?styleID=${payload}`)
			.then( ( response ) => {
				commit('getProductSet', response);
			})
			.catch(function (error) {
				console.log(error);
			})
		}
	}
});

function groupVariation (list, productSet){
	    let output = [];
	    let filterProducts = list.forEach( (size, index) => {
	        let sum = 0
	        productSet.forEach(product => {
	            if ( product.size === size )
	            { 
	              sum += +product.quantity;
	              output[index] = {
	                size: size,
	                quantity: sum
	              }
	            }
	        })
	    });

	    
	}

function getOutOfStockVariation (list, productSet){

	let groupVariation = [];
	    let filterProducts = list.forEach( (size, index) => {
	        let sum = 0
	        productSet.forEach(product => {
	            if ( product.size === size )
	            { 
	              sum += +product.quantity;
	              groupVariation[index] = {
	                size: size,
	                quantity: sum
	              }
	            }
	        })
	    });

	let zeroProduct = groupVariation.filter( e =>{
	  return e.quantity === 0
	});
	
	let output = []
	  zeroProduct.forEach(e => {
	    output.push(e.size)
	})

	return output;
}