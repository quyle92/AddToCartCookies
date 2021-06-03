import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import Helper from './helper';
let _helper = new Helper()


export default  new Vuex.Store({
	state:
	{	
		selectedProduct: {},
		priceRange: [],
		selectedFullNumber:'',
		productsOnCart:'',
		totalQuantity: window.totalQuantity,
		selectedStyleSet: [],
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
        maxQuantityArr:[],
        lastSelectedProduct: {}


	},
	getters:
	{


	},
	mutations:
	{
		SET_SELECTED_STYLE_SET( state, payload )
		{
			let selectedStyleSet = payload.data;
			let sizeList = [];

			this.state.sizeList.forEach(size => {
			  sizeList.push(Object.values(size)[0])
			})
			let colorList = [];
			this.state.colorList.forEach(color => {
			  colorList.push(Object.values(color)[0])
			})
			this.state.outOfStockSizeAll = _helper.getOutOfStockVariation(sizeList, selectedStyleSet);

			this.state.outOfStockColorAll = _helper.getOutOfStockVariation(colorList, selectedStyleSet);

			this.state.selectedStyleSet = selectedStyleSet;
			//this.state.selectedProduct = selectedStyleSet[0];

			//remove item where its quantity is < 0
           	let inStockStyleSet = selectedStyleSet.filter( e =>  e.quantity > 0 ); 

           	//get totalQuantity
	      	let totalQuantity = 0;
            let priceRange = [];
            inStockStyleSet.forEach( (item) => {
            	totalQuantity += item.quantity;
            	priceRange.push( +item.price )
            });
            this.state.totalQuantity = totalQuantity;
       

          	//set min price /max price
          	let minPrice = Math.round(_.min(priceRange));
            let maxPrice = Math.round(_.max(priceRange));

            this.state.priceRange['min(price)'] = minPrice;
			this.state.priceRange['max(price)'] = maxPrice;

		},

		SET_TOTAL_QUANTITY( state, payload ){
		
			this.state.totalQuantity = payload;
		},

		SET_PRICE_RANGE( state, payload ){
			this.state.priceRange['min(price)'] = payload.minPrice;
			this.state.priceRange['max(price)'] = payload.maxPrice;
		
		},

		SET_SELECTED_PRICE( state, payload ){
			this.state.selectedPrice = payload;
		},

		SET_SELECTED_PRODUCT( state, payload){
			this.state.selectedProduct = payload;
		},

		SET_LAST_SELECTED_PRODUCT( state, payload ){
			this.state.lastSelectedProduct = payload;
		}

	},
	actions:
	{
		getSelectedStyleSetAction({ commit }, payload){
			axios.get(`/getSelectedStyleSet?styleID=${payload}`)
			.then( ( response ) => {
				commit('SET_SELECTED_STYLE_SET', response);
			})
			.catch(function (error) {
				console.log(error);
			})
		}
	}
});


