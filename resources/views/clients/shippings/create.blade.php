@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="shipping-dev">

        <div class="loader-cover-custom" v-if="loading == true">
			<div class="loader-custom"></div>
		</div>

        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Crea envío
                    </div>
                    
                    <div class="card-toolbar">

                        <!--begin::Button-->
                        <button href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#articleModal" @click="create()">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                    <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>Añadir artículo</button>
                    
                        <!--end::Button-->
                    </div>
                    
                    
                </div>
                <div style="text-align: right;">
                    <small style="color: red;" v-if="errors.hasOwnProperty('products')">@{{ errors['products'][0] }}</small>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" id="address" v-model="address">
                                <small style="color: red;" v-if="errors.hasOwnProperty('address')">@{{ errors['address'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="total">Total: </label>
                                <input type="text" class="form-control" id="total" v-model="shippingTotal" readonly>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="tracking">Tracking</label>
                                <input type="text" class="form-control" id="total" v-model="tracking">
                                <small style="color: red;" v-if="errors.hasOwnProperty('tracking')">@{{ errors['tracking'][0] }}</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th style="width: 250px;">Factura</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(product, index) in products">
                                        <td>@{{ index + 1 }}</td>
                                        <td>@{{ product.name }}</td>
                                        <td>$ @{{ product.price }}</td>
                                        <td><img :src="product.imagePreview" alt="" style="width: 70%;"></td>
                                        <td>
                                            <button class="btn btn-success" data-toggle="modal" data-target="#articleModal" @click="edit(product, index)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-secondary" @click="erase(index)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12">
                            <p class="text-center">
                                <button class="btn btn-primary" @click="store()">Crear</button>
                            </p>
                        </div>
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        <!-- Modal-->
        <div class="modal fade" id="articleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Articulo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input class="form-control" v-model="product.name">
                        </div>

                        <div class="form-group">
                            <label for="name">Descripción</label>
                            <textarea class="form-control" rows="4" v-model="product.description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="name">Precio (USD)</label>
                            <input class="form-control" v-model="product.price" @keypress="isNumberDot($event)">
                        </div>
                       
                        <div class="form-group">
                            <label for="imagePreview">Copia Factura</label>
                            <input type="file" id="imagePreview-input" class="form-control" @change="onImageChange">
                            <img :src="product.imagePreview" style="width: 60%" />
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="addProduct()" v-if="action == 'create'">Crear</button>
                        <button type="button" class="btn btn-primary font-weight-bold"  @click="update()" v-if="action == 'edit'">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#shipping-dev',
            data() {
                return {
                    address:"{{ \Auth::user()->address }}",
                    total:0,
                    action:"create",
                    productIndex:"",
                    product:{
                        name:"",
                        price:"",
                        image:"",
                        imagePreview:"",
                        description:""
                    },
                    description:"",
                    tracking:"",
                    products:[],
                    errors:[],
                    loading:false
                }
            },
            computed: {
                shippingTotal: function () {
                     
                    var shippingTotalVar = 0
                    this.products.forEach((data) => {

                        shippingTotalVar += parseFloat(data.price)

                    })

                    return shippingTotalVar

                }
            },
            methods: {

                addProduct(){

                    if(this.product.name == ""){
                        swal({
                            text:"Debe agregar un nombre al producto",
                            icon:"error"
                        })
                    }
                    else if(this.product.description == ""){

                        swal({
                            text:"Debe agregar la descripción del producto",
                            icon:"error"
                        })

                    }
                    else if(this.product.price == ""){
                        swal({
                            text:"Debe agregar un precio al producto",
                            icon:"error"
                        })
                    }
                    else if(this.product.image == ""){

                        swal({
                            text:"Debe agregar la imagen de la factura del producto",
                            icon:"error"
                        })

                    }else{

                        swal({
                            title:"¡Genial!",
                            text:"Producto agregado",
                            icon:"success"
                        }).then(() => {

                            this.products.push({name: this.product.name, description: this.product.description, price: this.product.price, image: this.product.image, imagePreview: this.product.imagePreview})

                            this.product.name=""
                            this.product.description=""
                            this.product.price=""
                            this.product.image=""
                            this.product.imagePreview = ""
                            $("#imagePreview-input").val(null)

                        })

                    }
                    

                },
                onImageChange(e){
                    this.product.image = e.target.files[0];

                    this.product.imagePreview = URL.createObjectURL(this.product.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createImage(files[0]);
                },
                createImage(file) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = (e) => {
                        vm.product.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                create(){
                    this.action = "create"
                    this.product.name=""
                    this.product.description=""
                    this.product.price=""
                    this.product.image=""
                    this.product.imagePreview = ""
                    $("#imagePreview-input").val(null)
                },
                edit(product, index){
                    this.action = "edit"
                    this.productIndex = index
                    this.product.name = product.name
                    this.product.description= product.description
                    this.product.price= product.price
                    this.product.image= product.image
                    this.product.imagePreview = product.imagePreview

                },
                update(){

                    if(this.product.name == ""){
                        swal({
                            text:"Debe agregar un nombre al producto",
                            icon:"error"
                        })
                    }
                    else if(this.product.description == ""){

                        swal({
                            text:"Debe agregar la descripción del producto",
                            icon:"error"
                        })

                    }
                    else if(this.product.price == ""){
                        swal({
                            text:"Debe agregar un precio al producto",
                            icon:"error"
                        })
                    }
                    else if(this.product.image == ""){

                        swal({
                            text:"Debe agregar la imagen de la factura del producto",
                            icon:"error"
                        })

                    }else{

                        this.products[this.productIndex].name = this.product.name
                        this.products[this.productIndex].description = this.product.description
                        this.products[this.productIndex].price = this.product.price
                        this.products[this.productIndex].image = this.product.image
                        this.products[this.productIndex].imagePreview = this.product.imagePreview

                        swal({
                            title:"¡Genial!",
                            text:"Producto actualizado",
                            icon:"success"
                        })

                    }

                },
                isNumberDot(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                },
                erase(index){
                    this.products.splice(index, 1)
                    swal({
                        title:"¡Genial!",
                        text:"Producto eliminado",
                        icon:"success"
                    })
                },
                store(){
                    this.loading = true
                    this.errors = []
                    axios.post("{{ url('clients/shipping/store') }}", {tracking: this.tracking, address: this.address, products: this.products}).then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title:"Genial!",
                                text: res.data.msg,
                                icon:"success"
                            }).then(() => {
                                window.location.href="{{ url('/') }}"
                            })

                        }else{

                            swal({
                                title:"Lo sentimos!",
                                text: res.data.msg,
                                icon:"error"
                            })
                        
                        }

                    })
                    .catch(err => {
                        this.loading = false
                        swal({
                            text:"Hay unos campos que debe revisar",
                            icon:"error"
                        })
                        this.errors = err.response.data.errors
                    })

                }
                
            },
            

        })
    </script>


@endpush
