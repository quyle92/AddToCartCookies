<template>
    <div class="container" id="productPage">
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    <div class="preview-pic tab-content">
                      <div class="tab-pane active" id="pic-1"><img 
                    :src="selectedStyleSet[0].picture" v-if="selectedStyleSet[0]"/> <!--(1)-->
                    </div>
                      <div class="tab-pane" id="pic-2"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-3"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-4"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-5"><img src="http://placekitten.com/400/252" /></div>
                    </div>
                    <ul class="preview-thumbnail nav nav-tabs">
                      <li class="active"><a data-target="#pic-1" data-toggle="tab"><img 
                       :src="selectedStyleSet[0].picture" v-if="selectedStyleSet[0]"
                       /></a></li>
                      <li><a data-target="#pic-2" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-3" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-4" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-5" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                    </ul>
                </div>
                <div class="details col-md-6">
                    <h3 class="product-title" v-if="selectedStyleSet[0]"> {{selectedStyleSet[0].style}} | 
                        <small>{{selectedProduct.fullNumber}}</small> </h3>
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
                      ><span >${{priceRange['min(price)']}} - ${{priceRange['max(price)']}}</span>
                      <del>{{priceRangeOriginal}}</del>
                    </h4>
                    <h4 
                      class="price"
                      v-else
                      ><span>$ {{selectedPrice}}</span>
                      <del>{{selectedPriceOriginal}}</del>
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

                       <button class="product-variation" data-toggle="tooltip" 
                           v-for="(size, index) in sizeList" :key='index'
                            :title="size"
                             @click.prevent="selectSize(size)" 
                             :class="[
                             size===sizeColor.size ? selectedClass : '',
                             {
                                'disabled': outOfStockSize.includes( size ) || outOfStockSizeAll.includes( size )
                             }
                             ] "
                            > 
                           {{size}}
                        </button>
                    </h6>
                    <h6 class="colors">colors:
                     
                      <button class="product-variation" data-toggle="tooltip" 
                       v-for="(color, index) in colorList" :key='index'
                        :title="color"
                         @click.prevent="selectColor(color)" 
                          :class="[
                             color===sizeColor.color ? selectedClass : '',
                             {
                                'disabled': outOfStockColor.includes( color )
                             }
                             ] "
                        > 
                       {{color}}
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
            'styleId'
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
              'productsOnCart', 
              'selectedPrice'  ,
              'selectedStyleSet' , 
              'sizeColor',
              'outOfStockSize',
              'outOfStockColor',
              'sizeList',
              'colorList'
            ]),


        },
        methods: {
             selectSize(size){
                //khi click vào disabled size thì sẽ cho ko click đc tick
                if(this.outOfStockSize.indexOf(size) !== -1 ){
                    return
                }


                // khi click lại vào chính button đó
                if( size === this.sizeColor.size ){
                    this.sizeColor.size = '';
                    this.getMinMaxQuantity( this.sizeColor.color );
                    return;
                }

                //for css only
                this.sizeColor.size = '';
                this.sizeColor.size = size;
               

                //process logic
                this.getMinMaxQuantity( size);

            },
             selectColor(color){
               
                //khi click vào disabled color thì sẽ cho ko click đc tick
                if(this.outOfStockColor.indexOf(color) !== -1 ){
                    return
                }
                
                // khi click lại vào chính button đó
                if( color === this.sizeColor.color ){
                    this.sizeColor.color = '';
                    this.getMinMaxQuantity( this.sizeColor.size );
                    return;
                }

                //for css only
                this.sizeColor.color = '';
                this.sizeColor.color = color;
               

                //process logic
               this.getMinMaxQuantity( color);
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
                this.selectedStyleSet.forEach( item => {
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
                    this.$store.state.selectedProduct = {};
                    this.bothSizeColor( );
                  }

              //khi size + color button cùng ko đc tick
                  if( this.sizeColor.size.length === 0 && this.sizeColor.color.length === 0 )
                  { 
                     this.neitherSizeColor( );
                  }
    
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
                  style_id: this.selectedProduct.style_id,
                  style:  this.selectedProduct.style,
                  fullNumber: this.selectedProduct.fullNumber,
                  image: this.selectedProduct.picture,
                  size: this.sizeColor.size,
                  color: this.sizeColor.color,
                  price: this.selectedPrice,
                  quantity: this.quantity,
                  date: new Date(), 
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
              //console.log(products)
              localStorage.setItem('products', JSON.stringify( products ) ) ;

              this.$store.state.productsOnCart = JSON.parse( localStorage.getItem('products') );
               alert('Add to cart successfully');
          },
        },
        created() { 

            this.$store.state.sizeList = this.sizes;
            this.$store.state.colorList = this.colors;
            


            this.$store.dispatch('getSelectedStyleSetAction', this.styleId);

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


<!-- Note:
(1) must have v-if to  trigger a truthy expression. Ref:https://forum.vuejs.org/t/getting-a-typeerror-warning-but-app-works-fine/39307