import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default  new Vuex.Store({
	state:
	{
		selectedFullNumber:'',
		selectedSize:''
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