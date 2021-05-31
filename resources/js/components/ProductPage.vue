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
                    <h3 class="product-title"> {{selectedProduct.style}} </h3>
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
                      ><span >${{priceRange['min(price)']}} - {{priceRange['max(price)']}}</span>
                      <del>{{priceRangeOriginal}}</del>
                    </h4>
                     <h4 
                      class="price"
                      v-else
                      ><span>$ {{selectedPrice}}</span>
                      <del>{{selectedPriceOriginal}}</del>
                    </h4>
                    {{selectedPrice}}
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

                       <button class="product-variation" data-toggle="tooltip" 
                           v-for="(item, index) in sizeList" :key='item.id'
                            :title="item.size"
                             @click.prevent="selectSize(item)" 
                             :class="[
                             item.size===sizeColor.size ? selectedClass : '',
                             {
                                'disabled': outOfStockSize.includes( item.size )
                             }
                             ] "
                            > 
                           {{item.size}}
                        </button>
                    </h6>
                    <h6 class="colors">colors:
                     
                      <button class="product-variation" data-toggle="tooltip" 
                       v-for="(item, index) in colorList" :key='item.id'
                        :title="item.color"
                         @click.prevent="selectColor(item)" 
                          :class="[
                             item.color===sizeColor.color ? selectedClass : '',
                             {
                                'disabled': outOfStockColor.includes( item.color )
                             }
                             ] "
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
import { mapState } from 'vuex'
import { mapGetters } from 'vuex'
    export default {
        props: [
            'sizes',
            'colors',
            'priceRangeOnInit'
        ],
        data: () => ({
            //selectedProduct: {},
            
            selectedClass: 'font-weight-bold selected',
            quantity: 1,
            priceRangeOriginal:'',
            selectedPriceOriginal:''

            
        }),
        computed: {
          ...mapState([
              'selectedProduct',
              'totalQuantity'  ,
              'selectedFullNumber'  ,
              'priceRange' , 
              'selectedPrice'  ,
              'productSet' , 
              'sizeColor',
              'outOfStockSize',
              'outOfStockColor',
              'sizeList',
              'colorList'
            ]),


        },
        methods: {
             selectSize(item){
                //khi click vào disabled size thì sẽ cho ko click đc tick
                if(this.outOfStockSize.indexOf(item.size) !== -1 ){
                    return
                }


                // khi click lại vào chính button đó
                if( item.size === this.sizeColor.size ){
                    this.sizeColor.size = '';
                    this.getMinMaxQuantity( this.sizeColor.color );
                    return;
                }

                //for css only
                this.sizeColor.size = '';
                this.sizeColor.size = item.size;
               

                //process logic
                this.getMinMaxQuantity( item.size);

            },
             selectColor(item){
                
                //khi click vào disabled color thì sẽ cho ko click đc tick
                if(this.outOfStockColor.indexOf(item.color) !== -1 ){
                    return
                }
                
                // khi click lại vào chính button đó
                if( item.color === this.sizeColor.color ){
                    this.sizeColor.color = '';
                    this.getMinMaxQuantity( this.sizeColor.size );
                    return;
                }

                //for css only
                this.sizeColor.color = '';
                this.sizeColor.color = item.color;
               

                //process logic
               this.getMinMaxQuantity( item.color);
            },
            add(){   
             let value = this.getInputValue();  
                if( value < this.totalQuantity ){
                  this.quantity++
                }
                else{
                  alert('stop: max reached!');
                }
            },
            substract(){ 
             let value = this.getInputValue(); 
                if( value > 1 ){
                 this.quantity--
                }
                else{
                  alert('stop: min reached!');
                }
                 
            },
            getInputValue(){
                return $('input#quantity.form-control.input-number').val();
            },
            getMinMaxQuantity(variation = null)
            {
                let result = [];
                this.productSet.forEach( item => {
                    item.size === variation || item.color === variation ? result.push(item) : ''
                });
   
              //khi hoặc size hoặc color đc tick
                  if( this.sizeColor.size.length !== 0 && this.sizeColor.color.length === 0 || 
                    this.sizeColor.size.length === 0 && this.sizeColor.color.length !== 0
                    )
                  {
                    this.eitherSizeColor( result )
                  } 

              //khi size + color cùng đc tick 
                  if( this.sizeColor.size.length !== 0 && this.sizeColor.color.length !== 0 )
                  {
                    this.bothSizeColor( );
                  }

              //khi size + color button cùng ko đc tick
                  if( this.sizeColor.size.length === 0 && this.sizeColor.color.length === 0 )
                  { 
                     this.neitherSizeColor( );
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
          },
          addToCart(){
              if(this.sizeColor.size.length === 0 || this.sizeColor.color.length === 0)
              {
                alert('please select size + price');
                return;
              }
              let products =[];

              let newItem =  {
                  style_id: this.selectedProduct.id,
                  style:  this.selectedProduct.style,
                  fullNumber: this.selectedFullNumber,
                  image: this.selectedProduct.colors_for_one_style_only[0].pivot.picture,
                  size: this.sizeColor.size,
                  color: this.sizeColor.color,
                  price: this.selectedPrice,
                  quantity: this.quantity
              };

              if( localStorage.getItem('products') ){

                  products = JSON.parse( localStorage.getItem('products') );

                  if( this.$Helper.containsObject(  products, newItem ) ){
                     for(let item of products){
                      if( item.fullNumber === newItem.fullNumber ){
                        item.quantity += newItem.quantity;
                      }
                      
                    }
                    
                  }
                  else{
                    products.push( newItem );
                  }


           
              }
              else{
                 products.push(newItem);
              }
              

              localStorage.setItem('products', JSON.stringify( products ) ) ;

              alert('Add to cart successfully');
          },
        },
        created() { 

            this.$store.state.sizeList = this.sizes;
            this.$store.state.colorList = this.colors;
            
            let result = this.productSet;

            //bring priceRange to $store
            this.$store.state.priceRange = this.priceRangeOnInit;
            let minPrice = Math.round(Number(this.priceRange['min(price)'] * 1.2));
            let maxPrice = Math.round(Number(this.priceRange['max(price)'] * 1.2));
           
            this.priceRangeOriginal =  `$${minPrice} - $${maxPrice}`;

            let styleID = this.selectedProduct.colors_for_one_style_only[0].pivot.style_id;
            this.$store.dispatch('getProductSetAction', styleID)

            vm.$on('getPriceRangeOriginal', (priceRange) => {

                let minPrice =  Math.round(Number( _.min(priceRange) * 1.2 ) );
                let maxPrice =  Math.round(Number( _.max(priceRange) * 1.2 ) );
                this.priceRangeOriginal = `$${minPrice} - $${maxPrice}`;
               
            });

            vm.$on('getSelectedPriceOriginal', (price) => {

               this.selectedPriceOriginal = '$' + Math.round( Number( price * 1.2 ) );
               
            });

        },
        watch: {
            
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

#productPage  .product-variation:not(.disabled):hover, .selected {
    color: #ee4d2d;
    border-color: #ee4d2d !important;
   
}

.disabled{
  color: rgba(0,0,0,.26);
  cursor: not-allowed;
}
</style>