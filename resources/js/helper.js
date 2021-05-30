import isEqual  from 'lodash';

export default class Helper {

	containsObject(list, obj) 
	{
		for(let item of list ){
			if(item.fullNumber === obj.fullNumber){
				return true;
			}
		}

		return false;

	} 

	isInProductSet( productSet, obj ) 
	{
		for(let item of productSet ){
			if(item.style_id === obj.style_id){
				return true;
			}
		}

		return false;
	}



}