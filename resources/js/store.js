import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default  new Vuex.Store({
	state:
	{	
		selectedProduct: window.product,
		priceRange:  window.priceRange,
		selectedFullNumber:'',
		selectedSize:'',
		totalQuantity: window.totalQuantity,

	},
	getters:
	{
		getSelectedSize(state){
			return state.selectedSize;
		},
	},
	mutations:
	{
		
	},
	actions:
	{

	}
});