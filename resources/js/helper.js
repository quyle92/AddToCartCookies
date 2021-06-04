import isEqual  from 'lodash';

export default class Helper {

	containsObject(list, obj) 
	{//console.log(list, obj);
		for(let item of list ){
			if(item.fullNumber === obj.fullNumber){//console.log('true');debugger
				return true;
			}
		}

		return false;

	} 

	isInselectedStyleSet( selectedStyleSet, obj ) 
	{
		for(let item of selectedStyleSet ){
			if(item.style_id === obj.style_id){
				return true;
			}
		}

		return false;
	}

	getOutOfStockVariation (list, selectedStyleSet)
	{
		let groupVariation = [];
	    let filterProducts = list.forEach( (size, index) => {
	        let sum = 0
	        selectedStyleSet.forEach(style => {
	            if ( style.size === size )
	            { 
	              sum += +style.quantity;
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

	isObjEmpty(obj) {
	    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
	    }
	    return true;
	}

	



}