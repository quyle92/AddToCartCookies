<template>
    <div class="container" id="productPage">
    <div class="card">
        <div class="container-fliud">
            <div class="wrapper row">
                <div class="preview col-md-6">
                    
                    <div class="preview-pic tab-content">
                      <div class="tab-pane active" id="pic-1"><img v-bind:src="selectedProduct.picture" /></div>
                      <div class="tab-pane" id="pic-2"><img src="" /></div>
                      <div class="tab-pane" id="pic-3"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-4"><img src="http://placekitten.com/400/252" /></div>
                      <div class="tab-pane" id="pic-5"><img src="http://placekitten.com/400/252" /></div>
                    </div>
                    <ul class="preview-thumbnail nav nav-tabs">
                      <li class="active"><a data-target="#pic-1" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-2" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-3" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-4" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                      <li><a data-target="#pic-5" data-toggle="tab"><img src="http://placekitten.com/200/126" /></a></li>
                    </ul>
                    
                </div>
                <div class="details col-md-6">
                    <h3 class="product-title">{{selectedProduct.productName}} <small>Code: {{selectedProduct.fullNumber}} </small></h3>
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
                    <h4 class="price"><span>${{selectedPrice}}</span></h4>
                    <p class="vote"><strong>91%</strong> of buyers enjoyed this product! <strong>(87 votes)</strong></p>
                    <h5 class="sizes">sizes:
                       
                        <span class="size " data-toggle="tooltip" :title="item.size" 
                         v-for="(item, index) in selectedProduct.prices" :key='item.id'
                          > 
                         <a href="" @click.prevent="selectSize(item.price, item.size)">{{item.size}}</a>
                        </span>
                        
                    </h5>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-left-minus btn btn-danger btn-number"  data-type="minus" data-field="" @click="quantity--">
                                  -
                                </button>
                            </span>
                            <input type="text" id="quantity" name="quantity" class="form-control input-number" min="1" max="100" v-model="quantity">
                            <span class="input-group-btn">
                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field=""  @click="quantity++">
                                    +
                                </button>
                            </span>
                        </div>
                    </div>
                    <h5 class="colors">colors:
                        <a href=""><span class="color orange not-available" data-toggle="tooltip" title="Not In store"></span></a>
                        <a href=""><span class="color green"></span></a>
                        <a href=""><span class="color blue"></span></a>
                    </h5>
                    <div class="action">
                        <button class="add-to-cart btn btn-default" type="button" @click.prevent="addToCart">add to cart</button>
                        <button class="like btn btn-default" type="button"><span class="fa fa-heart"></span></button>
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
            'product'
        ],
        data: () => ({
            selectedProduct: {},
            sizePrice: [],
            selectedPrice: '',
            selectedSize: '',
            selectedClass: '',
            highlightedText: 'font-weight-bold text-danger',
            quantity: 1
        }),

        methods: {
            selectSize(price, size){
                this.selectedPrice = price;
                this.selectedSize = size;
                //this.selectedClass = "font-weight-bold text-danger"
                //console.log( $('span[title=' + size + ']') );
                let sizeTags = $('h5.sizes span');

                $( sizeTags ).each(function( i, e ) {
                    //console.log(e);
                   $(e).removeClass('font-weight-bold text-danger');
                });
                $('span[title=' + size + ']').addClass('font-weight-bold text-danger');
            },
            addToCart(){
                axios.post('/addToCart', {
                    fullNumber: this.selectedProduct.fullNumber,
                    productName:  this.selectedProduct.productName,
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
            increment(){
                 this.quantity = this.quantity++
            },
            decrement(){
                 this.quantity = this.quantity--
            }
        },
        created() { 
           this.selectedProduct = this.product;

           this.product.prices.forEach( (item) => {
                delete item.id;
                delete item.product_id;
                let [price , size] = Object.values(item); 
                let result = {};
                result[size] = price;
                this.sizePrice.push(result);
           });
           this.selectedPrice = Object.values( this.sizePrice[0] )[0];
           this.selectedSize = Object.keys( this.sizePrice[0] )[0];
           let defaultSize = Object.keys( this.sizePrice[0] )[0];
           $('span[title=' + defaultSize + ']').addClass(this.highlightedText);
           
           
        },
        mounted () {
             $('span[title=' + this.selectedSize + ']').addClass(this.highlightedText);
        }
    }
</script>
