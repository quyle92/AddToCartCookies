import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import Helper from './helper';
let _helper = new Helper()


export default  new Vuex.Store({
	state:
	{	
		selectedProduct: window.product,
		priceRange: '',
		selectedFullNumber:'',
		productsOnCart:'',
		totalQuantity: window.totalQuantity,
		productSet: [],
		selectedPrice:0,
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

			this.state.outOfStockSize = _helper.getOutOfStockVariation(sizeList, productSet);
			this.state.outOfStockSizeAll = _helper.getOutOfStockVariation(sizeList, productSet);

			this.state.outOfStockColor = _helper.getOutOfStockVariation(colorList, productSet);
			this.state.outOfStockColorAll = _helper.getOutOfStockVariation(colorList, productSet);

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


