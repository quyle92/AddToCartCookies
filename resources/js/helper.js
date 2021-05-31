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

	 getOutOfStockVariation (list, productSet){

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

	



}