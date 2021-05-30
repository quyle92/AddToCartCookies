import Vue from 'vue';
import Vuex from 'vuex';

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
		sizeColor:{
          size: '',
          color: ''
        },


	},
	getters:
	{


	},
	mutations:
	{
		getProductSet( state, payload ){
			this.state.productSet = payload.data;
			
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