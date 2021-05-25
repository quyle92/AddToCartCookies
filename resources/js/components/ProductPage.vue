<template>
    <div class="container" id="productPage">
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    
                    <div class="preview-pic tab-content">
                      <div class="tab-pane active" id="pic-1"><img v-bind:src="selectedProduct.colors_for_one_style_only[0].pivot.picture" /></div>
                      <div class="tab-pane" id="pic-2"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-3"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-4"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-5"><img src="http://placekitten.com/400/252" /></div>
                    </div>
                    <ul class="preview-thumbnail nav nav-tabs">
                      <li class="active"><a data-target="#pic-1" data-toggle="tab"><img :src="selectedProduct.colors_for_one_style_only[0].pivot.picture" /></a></li>
                      <li><a data-target="#pic-2" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-3" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-4" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-5" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                    </ul>
                    
                </div>
                <div class="details col-md-6">
                    <h3 class="product-title"> <small>Code:  {{selectedProduct.colors_for_one_style_only[0].pivot.fullNumber}}</small></h3>
                    <div class="rating">
                        <div class="stars">
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <span class="review-no">41 reviews</span>
                    </div>
                    <p class="product-description">Suspendisse quos? Tempus cras iure temporibus? Eu laudantium cubilia sem sem! Repudiandae et! Massa senectus enim minim sociosqu delectus posuere.</p>

                    <h4 
                      class="price"
                      v-if=" ! selectedPrice"
                      ><span>$ {{this.priceRange['min(price)']}} - $ {{this.priceRange['max(price)']}}</span>
                    </h4>
                     <h4 
                      class="price"
                      v-else
                      ><span>$ {{this.selectedPrice}}</span>
                    </h4>

                    <p class="vote"><strong>91%</strong> of buyers enjoyed this product! <strong>(87 votes)</strong></p>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="" @click="substract()">
                                  -
                                </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" data-min="1" data-max="100" v-model="quantity" 
                            @change="changeQuantity"
                            @focusin="focus"
                            >
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""  @click="add()">
                                    +
                                </button>
                            </span>
                        </div>
                    </div>
                    <h6 class="sizes mt-3">sizes:

                       <button class="product-variation" data-toggle="tooltip" title="Not In store"
                           v-for="(item, index) in sizeList" :key='item.id'
                            :title="item.size"
                             @click.prevent="selectSize(item.size)" 
                            > 
                           {{item.size}}
                        </button>
                    </h6>
                    <h6 class="colors">colors:
                       
                          <button class="product-variation" data-toggle="tooltip" title="Not In store"
                           v-for="(item, index) in colorList" :key='item.id'
                            :title="item.color"
                             @click.prevent="selectColor(item.color)" 
                            > 
                           {{item.color}}
                          </button>
                       
                    </h6>
                    <div class="action">
                        <button class="add-to-cart btn btn-default" type="button" @click.prevent="addToCart">add to cart</button>
                        <button class="like btn btn-default" type="button"><span class="fa fa-heart"></span></button>
                        <span>{{this.totalQuantity}} items available</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</template>

<script>

    export default {
        props: [
            'sizes',
            'colors',
        ],
        data: () => ({
            selectedProduct: {},
            priceRange: {},
            sizeList: [],
            colorList:[],
            selectedClass: 'font-weight-bold selected',
            sizeColor:{},
            minPrice: '',
            maxPrice: '',
            selectedPrice: '',
            totalQuantity: '',
            quantity: 1
        }),

        methods: {
            async selectSize(size){
                this.quantity = 1;
                // khi click lại vào chính button đó
                if( size === this.sizeColor.size ){
                    $('button[title=' + size + ']').removeClass(this.selectedClass);
                    this.sizeColor.size = '';
                    let params = this.getParams();
                    
                    let response = await axios.post('/getPriceQuantity', params);
                    this.getMinMaxQuantity(response);
                    return;
                }

                //for css only
                let sizeTags = $('h6.sizes button');
                $( sizeTags ).each(function( i, e ) {
                   $(e).removeClass( this.selectedClass );

                }.bind(this));

                $('button[title=' + size + ']').addClass(this.selectedClass);

                //send to server
                this.sizeColor.size = size;
                let params = this.getParams();
                let response = await axios.post('/getPriceQuantity', params);
                this.getMinMaxQuantity(response);

            },
            async selectColor(color){
                this.quantity = 1;
                // khi click lại vào chính button đó
                if( color === this.sizeColor.color ){
                    $('button[title=' + color + ']').removeClass(this.selectedClass);
                    this.sizeColor.color = '';
                    let params = this.getParams();
                    
                    let response = await axios.post('/getPriceQuantity', params);
                    this.getMinMaxQuantity(response);
                    return;
                }                

                 //for css only
                let colorTags = $('h6.colors button');

                $( colorTags ).each(function( i, e ) {
                    //console.log(e);
                   $(e).removeClass( this.selectedClass );
                }.bind(this));
                $('button[title=' + color + ']').addClass(this.selectedClass);

                 //send to server
                this.sizeColor.color = color;
                let params = this.getParams();
                let response = await axios.post('/getPriceQuantity', params);
                this.getMinMaxQuantity(response);
            },
            addToCart(){
                axios.post('/addToCart', {
                    fullNumber: this.ber,
                    productName:  this.Name,
                    price: this.selectedPrice,
                    size: this.selectedSize,
                    quantity: this.quantity,
                  })
                  .then(function (response) {
                    console.log(response);
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
            },
            add(){   
             let value = this.getInputValue();  
                if( value < this.totalQuantity ){
                  $('input').val(++value);
                }
                else{
                  alert('stop: max reached!');
                }
            },
            substract(){ 
             let value = this.getInputValue(); 
                if( value > 1 ){
                 $('input').val(--value);
                }
                else{
                  alert('stop: min reached!');
                }
                 
            },
            getInputValue(){
                return $('input#quantity.form-control.input-number').val();
            },
            getMinMaxQuantity(response){
              let result = response.data;
                
               //khi hoặc size hoặc color đc tick
                if( result.length > 1)
                {
                    let totalQuantity = 0;
                    let priceRange = [];

                    result.forEach( (item) => {
                        totalQuantity += item.quantity;
                        priceRange.push( item.price )
                    });
                    this.totalQuantity = totalQuantity;

                    let minPrice = _.min(priceRange);
                    let maxPrice = _.max(priceRange);
                    this.priceRange['min(price)'] = minPrice;
                    this.priceRange['max(price)'] = maxPrice;

                    //cho selectedPrice = '' để priceRange đc render trên template
                    this.selectedPrice = '';
                   
                } 

                 //khi size + color cùng đc tick
                if( result.length === 1 )
                {   
                    let totalQuantity = 0;

                    result.forEach( (item) => {
                        totalQuantity += item.quantity;
                    });
                    this.totalQuantity = totalQuantity;
        
                    this.selectedPrice = result[0].price;
                }
            },
          getParams(){
              let styleID = {
                  styleID: this.selectedProduct.colors_for_one_style_only[0].pivot.style_id
              };
              let params = {
                ...styleID,
                ...this.sizeColor
              }
              
              return params;
          },
          focus(event){
            this.oldQuantity = parseInt( event.target.value );
          },
          changeQuantity(event){
            if( parseInt( event.target.value ) > this.totalQuantity )
            { 
              alert('stop: that\'s enough!');
              this.quantity = this.oldQuantity;
            }
          }
        },
        created() { 
          this.selectedProduct = this.$store.state.selectedProduct; console.log(this.selectedProduct)
          this.sizeList = this.sizes;
          this.colorList = this.colors;
          this.priceRange = JSON.parse(JSON.stringify(this.$store.state.priceRange));
          this.totalQuantity = this.$store.state.totalQuantity;
           
        },
        mounted () {
            
        }
    }
</script>

<style scoped>
#productPage  .product-variation {
    border: 1px solid rgba(0,0,0,.09);
    margin-right: 10px;
    background: #fff;
    box-sizing: border-box;
    padding: .25rem .75rem;
    margin: 7px 8px 8px 0;
    line-height: 37px;

}

#productPage  .product-variation:hover, .selected {
    color: #ee4d2d;
    border-color: #ee4d2d !important;
   
}
</style>
