@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="shipping-edit">

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
                        <h3 class="card-label">Envíos
                    </div>

                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                   
                    <div class="container-fluid">
                        <div class="row">
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        
                                        <label for="recipient">Destinatario</label>
                                        <div style="display: flex;">
                                            <input type="text" class="form-control" @click="showRecipientSearch()" id="recipient" autocomplete="off" v-model="recipientShowName">
                                            <button class="btn btn-success" data-toggle="modal" data-target="#recipientModal"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <small style="color:red;" v-if="errors.hasOwnProperty('recipientId')">@{{ errors['recipientId'][0] }}</small>
                                    </div>
                                
                                </div>
                                
                                @if(\Auth::user()->role_id <= 2)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tracking">Tracking number</label>
                                        <input type="text" class="form-control" v-model="tracking">
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tracking">Tracking number</label>
                                        <input type="text" class="form-control" v-model="tracking" :readonly="userId">
                                    </div>
                                </div>

                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="recipient">Imagen DNI (parte delantera)</label>
                                        <input type="file" class="form-control" @change="onImageChange" style="overflow:hidden;">
                                        <img :src="imagePreview" style="width: 40%" />
                                    </div>
                                
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="recipient">Imagen DNI (parte trasera)</label>
                                        <input type="file" class="form-control" @change="onImageChangeBack" style="overflow:hidden;">
                                        <img :src="imagePreviewBack" style="width: 40%" />
                                    </div>
                                
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">DNI o CE</label>
                                        <input type="text" class="form-control" v-model="clientDNI">
                                    </div>
                                    <!--<small style="color: red;" v-if="errors.hasOwnProperty('department')">@{{ errors['department'][0] }}</small>-->
                                
                                </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Departamento</label>
                                    <select class="form-control" @change="fetchProvinces()" v-model="department">
                                        <option v-for="department in departments" :value="department.id">@{{ department.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('department')">@{{ errors['department'][0] }}</small>
                            
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Provincia</label>
                                    <select class="form-control" v-model="province" @change="fetchDistricts()">
                                        <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('province')">@{{ errors['province'][0] }}</small>
                            
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Distrito</label>
                                    <select class="form-control" v-model="district">
                                        <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('district')">@{{ errors['district'][0] }}</small>
                            
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient">Dirección</label>
                                    <input type="text" class="form-control" v-model="address":readonly="userId"  @if(\Auth::user()->role_id == 3) readonly @endif>
                                </div>
                            </div>

                            <div class="col-md-6" v-if="resellers.length > 0">
                                <div class="form-group" >
                                    <label for="reseller">Reseller: @{{ reseller }}</label>
                                  
                                    <select class="form-control" v-model="resellerId">
                                        <option value="">Sin reseller</option>
                                        <option :value="resellers.id" v-if="resellers != ''">@{{ resellers.name }}</option>
                                    </select>
                            
                                </div>
                               
                            </div>
                            <div class="col-md-6" v-else>
                                <div class="form-group">
                                    <label for="address">Reseller</label>
                                    <select class="form-control" v-model="resellerId">
                                        <option value="">Sin reseller</option>
                                        <option :value="reseller.id" v-for="reseller in allResellers">@{{ reseller.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <textarea rows="4" id="description" class="form-control" v-model="description"></textarea>
                                    <small style="color:red;" v-if="errors.hasOwnProperty('description')">@{{ errors['description'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="agent">Piezas</label>
                                    <input type="text" class="form-control" v-model="pieces" @if(\Auth::user()->role_id == 3) readonly @endif>
                                </div>
                            </div>
                         

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="recipient">Tipo de paquete</label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" v-model="packageShowName" @click="showPackageSearch()"><button class="btn btn-success" data-toggle="modal" data-target="#packageModal"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <small style="color:red;" style="color:red;" v-if="errors.hasOwnProperty('packageId')">@{{ errors['packageId'][0] }}</small>

                                </div>
                            </div>

                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Largo (cm)</label>
                                    <input type="text" class="form-control" v-model="length" @if(\Auth::user()->role_id == 3) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Alto (cm)</label>
                                    <input type="text" class="form-control" v-model="height" @if(\Auth::user()->role_id == 3) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Ancho (cm)</label>
                                    <input type="text" class="form-control" v-model="width" @if(\Auth::user()->role_id == 3) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Peso (kg)</label>
                                    <input type="text" class="form-control" v-model="weight" @if(\Auth::user()->role_id == 3) readonly @endif>
                                    <span>@{{ pounds }} lb</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="text-center">
                                    <button class="btn btn-success" data-toggle="modal" data-target="#productModal" @click="create()">
                                        Agregar producto
                                    </button>
                                </p>
                            </div> 

                            <div class="col-md-12">
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
                                            <td>
                                                <div v-if="product.image == 'Sin factura'">
                                                    @{{ product.image }}
                                                </div>
                                                <div v-else>
                                                    <img :src="product.image" alt="" style="width: 70%;" v-if="product.fileType == 'image' || product.file_type == 'image'">
                                                    <span v-else> <a :href="product.image" target="_blank">PDF</a></span>
                                                </div>
                                                
                                            </td>
                                            <td>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#productModal" @click="edit(product, index)"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-secondary" @click="erase(index)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <p class="text-center">
                                    @if(\Auth::user()->role_id < 3)
                                        <button class="btn btn-primary" @click="updateInfo()">Actualizar</button>
                                        {{--@if($shipping->is_finished == 0)
                                        <button class="btn btn-secondary" @click="process()">Procesar</button>
                                        @endif--}}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>   

                    <!-- Modal-->
                    <div class="modal fade" id="recipientModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear destinatario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="recipientName">Nombre</label>
                                        <input type="text" class="form-control" id="recipientName" v-model="recipientName">
                                        <small style="color:red;" v-if="recipientErrors.hasOwnProperty('name')">@{{ recipientErrors['name'][0] }}</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipientEmail">Email</label>
                                        <input type="text" class="form-control" id="recipientEmail" v-model="recipientEmail">
                                        <small style="color:red;" v-if="recipientErrors.hasOwnProperty('email')">@{{ recipientErrors['email'][0] }}</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="recipientPhone">Teléfono</label>
                                        <input type="text" class="form-control" id="recipientPhone" v-model="recipientPhone">
                                        <small style="color:red;" v-if="recipientErrors.hasOwnProperty('phone')">@{{ recipientErrors['phone'][0] }}</small>
                                    </div> 



                                    <div class="form-group">
                                        <label for="recipientAddress">Dirección</label>
                                        <input type="text" class="form-control" id="recipientAddress" v-model="recipientAddress">
                                        <small style="color:red;" v-if="recipientErrors.hasOwnProperty('address')">@{{ recipientErrors['address'][0] }}</small>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="recipientCloseModal" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary font-weight-bold" @click="storeRecipient()">Crear</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal-->
                    <div class="modal fade" id="packageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Crear tipo de paquete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="packageName">Nombre</label>
                                        <input type="text" class="form-control" id="packageName" v-model="packageName">
                                        <small style="color:red;" v-if="packageErrors.hasOwnProperty('name')">@{{ packageErrors['name'][0] }}</small>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="packageCloseModal" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary font-weight-bold" @click="storePackage()">Crear</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Recipients Search-->
                    <div class="modal fade" id="recipientSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Buscar destinatario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="recipientSearch">Nombre</label>
                                        <input type="text" class="form-control" @keyup="recipientSearch()" id="recipientSearch" v-model="recipientQuery" autocomplete="off"> 
                                    </div>

                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action" style="cursor:pointer;" v-for="recipient in recipients" @click="selectRecipientId(recipient)">@{{ recipient.name }} @{{ recipient.lastname }}</li>
                                    </ul>

                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-primary font-weight-bold" @click="showNewRecipientModal()">Nuevo destinatario</button>
                                    <button type="button" id="recipientModalSearch" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Recipients Search-->

                    <!-- Modal Recipients Search-->
                    <div class="modal fade" id="packageSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Buscar tipos de empaques</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="recipientSearch">Nombre</label>
                                        <input type="text" class="form-control" v-model="packageQuery" @keyup="packageSearch()" autocomplete="off">
                                    </div>


                                    <ul class="list-group">
                                        <li class="list-group-item list-group-item-action" style="cursor:pointer;" v-for="package in packages" @click="selectPackageId(package)">@{{ package.name }}</li>
                                    </ul>

                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-primary font-weight-bold" @click="showNewPackageModal()">Nuevo empaque</button>
                                    <button type="button" id="packageModalSearch" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Recipients Search-->

                    <!-- Product Modal -->

                    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Articulo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Nombre</label>
                                                <input class="form-control" v-model="product.name">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Precio (USD)</label>
                                                <input class="form-control" v-model="product.price" @keypress="isNumberDot($event)">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="imagePreview">Copia Factura</label>
                                                <input type="file" style="overflow: hidden;" id="imagePreview-input" class="form-control" @change="onImageProductChange">
                                                <img :src="product.imagePreview" style="width: 60%" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="imagePreview">Imagen del producto</label>
                                                <input type="file" style="overflow: hidden;" id="imagePreview-input" class="form-control" @change="onImageProductPictureChange">
                                                <img :src="product.productImagePreview" style="width: 60%" />
                                            </div>
                                        </div>

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

                    <!-- Product Modal -->                  

                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

       

    </div>

@endsection

@push("scripts")

<script>
        const devArea = new Vue({
            el: '#shipping-edit',
            data() {
                return {
                    shippingId:"{{ $shipping->id }}",
                    recipients:[],
                    userId:"{{ $shipping->client ? $shipping->client->id : ''  }}",
                    userName:"{{ $shipping->client ? $shipping->client->name.' '.$shipping->client->lastname : '' }}",
                    recipientId:"{{ $shipping->client ? $shipping->client->id : '' }}",
                    recipientQuery:"",
                    recipientShowName:"{{ $shipping->client ? $shipping->client->name : '' }}",
                    packages:[],
                    packageId:"{{ $shipping->box ? $shipping->box->id : '' }}",
                    packageQuery:"",
                    packageShowName:"{{ $shipping->box ? $shipping->box->name : '' }}",
                    tracking:"{{ $shipping->tracking }}",
                    description:"{{ $shipping->description }}",
                    pieces:"{{ $shipping->pieces }}",
                    length:"{{ $shipping->length }}",
                    height:"{{ $shipping->height }}",
                    width:"{{ $shipping->width }}",
                    weight:"{{ $shipping->weight }}",
                    resellers:[],
                    resellerId:"{{ $shipping->reseller ? $shipping->reseller->id : '' }}",
                    reseller:"{{ $shipping->reseller ? $shipping->reseller->name : '' }}",
                    clientDNI:"{{ $shipping->client ? $shipping->client->dni : '' }}",
                    recipientName:"",
                    recipientEmail:"",
                    recipientPhone:"",
                    recipientAddress:"",
                    packageName:"",
                    errors:[],
                    recipientErrors:[],
                    packageErrors:[],
                    address:"{{ $shipping->address }}",
                    file:"",
                    fileType:"",
                    image:"",
                    imageBack:"",
                    imagePreview:"{{ $shipping->client->dni_picture ? $shipping->client->dni_picture : '' }}",
                    imagePreviewBack:"{{ $shipping->client->dni_picture_back ? $shipping->client->dni_picture_back : '' }}",
                    departments:[],
                    department:"{{ $shipping->client->department_id ? $shipping->client->department_id : ''  }}",
                    provinces:[],
                    province:"{{ $shipping->client->province_id ? $shipping->client->province_id : '' }}",
                    districts:[],
                    district:"{{ $shipping->client->district_id ? $shipping->client->district_id : '' }}",
                    products:JSON.parse('{!! $shipping->shippingProducts !!}'),
                    action:"create",
                    product:{
                        name:"",
                        price:"",
                        mage:"",
                        productImage:"",
                        imagePreview:"",
                        productImagePreview:""
                    },
                    loading:false,
                    allResellers:[]
                }
            },
            computed:{
                pounds(){
                    return this.weight * 2.20
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

                            this.products.push({name: this.product.name, description: this.product.description, price: this.product.price, image: this.product.image, imagePreview: this.product.imagePreview, fileType: this.fileType, productImage: this.product.productImage,productImagePreview: this.productImagePreview})

                            this.product.name=""
                            this.product.description=""
                            this.product.price=""
                            this.product.image=""
                            this.product.imagePreview = ""
                            this.product.productImage=""
                            this.product.productImagePreview = ""
                            $("#imagePreview-input").val(null)

                        })

                    }
                },
                fetchResellers(){

                    axios.get("{{ url('/resellers/fetch') }}"+"/"+this.recipientId).then(res => {

                        if(res.data.resellers != null){
                            this.resellers = res.data.resellers
                        }else{
                            this.resellers = []
                        }

                    })

                },
                fetchAllResellers(){

                    axios.get("{{ url('/resellers/fetch-all') }}").then(res => {

                        this.allResellers = res.data.resellers

                    })

                },
                recipientSearch(){

                //if(this.recipientQuery.length > 0){
                    axios.post("{{ url('recipients/search') }}", {search: this.recipientQuery}).then(res => {

                        this.recipients = res.data.recipients

                    })
                //}

                },
                selectRecipientId(recipient){


                    this.recipientId = recipient.id
                    this.recipientShowName = recipient.name
                    this.address = recipient.address
                    this.recipientQuery = ""
                    this.recipients = []

                    $("#recipientModalSearch").click();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '0px');
                    $('.modal-backdrop').remove();

                    this.fetchResellers()

                },
                packageSearch(){

                    if(this.packageQuery.length > 0){
                        axios.post("{{ url('packages/search') }}", {search: this.packageQuery}).then(res => {

                            this.packages = res.data.boxes

                        })
                    }

                },
                selectPackageId(package){

                    this.packageId = package.id
                    this.packageShowName = package.name
                    this.packageQuery = ""
                    this.packages = []

                    $("#packageModalSearch").click();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '0px');
                    $('.modal-backdrop').remove();

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
                isNumber(evt) {
                    evt = (evt) ? evt : window.event;
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                        evt.preventDefault();;
                    } else {
                        return true;
                    }
                },
                updateInfo(){

                    this.loading = true
                    axios.post("{{ url('shippings/update-info') }}", {shippingId: this.shippingId, recipientId: this.recipientId, packageId: this.packageId, tracking: this.tracking, description: this.description, pieces: this.pieces, length: this.length, height: this.height, width: this.width, weight: this.weight, address: this.address, resellerId: this.resellerId, dniPicture: this.image, products: this.products, department: this.department, province: this.province, district: this.district, clientDNI: this.clientDNI})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            }).then(res => {

                                window.location.href="{{ url('/home') }}"

                            });
                            
                            
                        }else{

                            swal({
                                title: "Lo sentimos!",
                                text: res.data.msg,
                                icon: "error"
                            });

                        }

                    })
                    .catch(err => {

                        alertify.error("Hay algunos campos que debe revisar")

                        this.loading = false
                        this.errors = err.response.data.errors
                    })

                },
                storeRecipient(){

                    this.loading = true
                    axios.post("{{ url('recipients/store') }}", {name: this.recipientName, email: this.recipientEmail, phone: this.recipientPhone, address: this.recipientAddress})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });
                            this.address = this.recipientAddress
                            this.recipientName = ""
                            this.recipientEmail = ""
                            this.recipientPhone = ""
                            this.recipientAddress = ""
                            this.recipientShowName = res.data.recipient.name
                            this.recipientId = res.data.recipient.id
                            this.recipients = []

                            $("#recipientCloseModal").click();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '0px');
                            $('.modal-backdrop').remove();
                            
                        }else{

                            swal({
                                title: "Lo sentimos!",
                                text: res.data.msg,
                                icon: "error"
                            });

                        }

                    })
                    .catch(err => {
                        alertify.error("Hay algunos campos que debe revisar")
                        this.loading = false
                        this.recipientErrors = err.response.data.errors
                    })

                },
                storePackage(){

                    this.loading = true
                    axios.post("{{ url('packages/store') }}", {name: this.packageName})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });
                            this.packageName = ""
                            this.packageShowName = res.data.box.name
                            this.packageId = res.data.box.id
                            this.packages = []

                            $("#packageCloseModal").click();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '0px');
                            $('.modal-backdrop').remove();
                            
                        }else{

                            swal({
                                title: "Lo sentimos!",
                                text: res.data.msg,
                                icon: "error"
                            });

                        }

                    })
                    .catch(err => {
                        alertify.error("Hay algunos campos que debe revisar")
                        this.loading = false
                        this.packageErrors = err.response.data.errors
                    })

                },
                process(){

                    swal({
                        title: "¿Estás seguro?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            
                            this.loading = true
                            axios.post("{{ url('shippings/process') }}", {shippingId: this.shippingId, packageId: this.packageId, description: this.description, pieces: this.pieces, length: this.length, height: this.height, width: this.width, weight: this.weight, address: this.address, resellerId: this.resellerId})
                            .then(res => {
                                this.loading = false
                                if(res.data.success == true){

                                    swal({
                                        title: "Perfecto!",
                                        text: res.data.msg,
                                        icon: "success"
                                    }).then(res => {

                                        window.location.href="{{ url('/home') }}"

                                    });
                                    
                                    
                                }else{

                                    swal({
                                        title: "Lo sentimos!",
                                        text: res.data.msg,
                                        icon: "error"
                                    });

                                }

                            })
                            .catch(err => {

                                alertify.error("Hay algunos campos que debe revisar")

                                this.loading = false
                                this.errors = err.response.data.errors
                            })

                        } 
                    });

                },
                showRecipientSearch(){

                    $("#recipientSearch").modal("show")

                },
                showPackageSearch(){

                    $("#packageSearch").modal("show")

                },
                showNewRecipientModal(){
                    $("#recipientModalSearch").click();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '0px');
                    $('.modal-backdrop').remove();

                    $("#recipientModal").modal("show")

                },
                showNewPackageModal(){
                    $("#packageModalSearch").click();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '0px');
                    $('.modal-backdrop').remove();

                    $("#packageModal").modal("show")

                },
                fetchDepartments(){

                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments

                    })

                },
                fetchProvinces(){

                    axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                        this.provinces = res.data.provinces

                    })

                },
                fetchDistricts(){

                    axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                        this.districts = res.data.districts

                    })

                },
                createdFetchDepartments(){
                    
                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments
                        axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                            this.provinces = res.data.provinces
                            axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                                this.districts = res.data.districts

                            })
                        })
                    })

                },
                edit(product, index){
                    
                    this.action = "edit"
                    this.productId = product.id
                    this.productIndex = index
                    this.product.name = product.name
                    this.product.description= product.description
                    this.product.price= product.price
                    this.product.fileType = product.file_type
                    if(this.productId != '' && this.productId != null){
                        this.product.imagePreview = product.image
                        this.product.productImagePreview= product.productImage
                    }else{
                        this.product.image = product.image
                        this.product.imagePreview = product.imagePreview

                        this.product.productImage= product.productImage
                        this.product.productImagePreview = product.productImagePreview

                    }
                    
                    
                    
                },
                update(){

                    if(this.product.name == ""){
                        swal({
                            text:"Debe agregar un nombre al producto",
                            icon:"error"
                        })
                    }
                    else if(this.product.price == ""){
                        swal({
                            text:"Debe agregar un precio al producto",
                            icon:"error"
                        })
                    }
                    else{

                        this.products[this.productIndex].name = this.product.name
                        this.products[this.productIndex].price = this.product.price
                        this.products[this.productIndex].image = this.product.image
                        this.products[this.productIndex].imagePreview = this.product.imagePreview
                        this.products[this.productIndex].fileType = this.fileType
                        this.products[this.productIndex].file_type = ""
                        this.products[this.productIndex].productImage = this.product.productImage
                        this.products[this.productIndex].productImagePreview =  this.productImagePreview

                        swal({
                            title:"¡Genial!",
                            text:"Producto actualizado",
                            icon:"success"
                        })

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
                create(){
                    this.action = "create"
                    this.product.name=""
                    this.product.description=""
                    this.product.price=""
                    this.product.image=""
                    this.product.imagePreview = ""
                    $("#imagePreview-input").val(null)
                },
                onImageChange(e){
                    this.image = e.target.files[0];

                    this.imagePreview = URL.createObjectURL(this.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createImage(files[0]);
                },
                createImage(file) {

                    this.file = file
                    this.fileType = file['type'].split('/')[0]
                    this.fileName = file['name']

                    if(this.fileType == "image"){

                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.image = e.target.result;
                        };
                        reader.readAsDataURL(file);

                    }else{

                        this.image = ""
                        this.imagePreview = ""

                        swal({
                            text:"Archivo no es una imagen",
                            icon:"error"
                        })

                    }

                    
                },
                onImageChangeBack(e){
                    this.imageBack = e.target.files[0];

                    this.imagePreviewBack = URL.createObjectURL(this.imageBack);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createImageBack(files[0]);
                },
                createImageBack(file) {

                    this.file = file
                    this.fileType = file['type'].split('/')[0]
                    this.fileName = file['name']

                    if(this.fileType == "image"){

                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.imageBack = e.target.result;
                        };
                        reader.readAsDataURL(file);

                    }else{

                        this.image = ""
                        this.imagePreview = ""

                        swal({
                            text:"Archivo no es una imagen",
                            icon:"error"
                        })

                    }

                    
                },
                onImageProductChange(e){

                    this.product.image = e.target.files[0];
                    this.product.imagePreview = URL.createObjectURL(this.product.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                        
                    this.createProductImage(files[0]);
                },
                onImageProductPictureChange(e){

                    this.product.productImage = e.target.files[0];
                    this.product.productImagePreview = URL.createObjectURL(this.product.productImage);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                        
                    this.createProductPicture(files[0]);
                },
                createProductImage(file) {

                    this.file = file
                    this.fileType = file['type'].split('/')[0]
                    this.fileName = file['name']

                    if(this.fileType == "image" || file["type"].indexOf("pdf") >= 0){


                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.product.image = e.target.result;
                        };
                        reader.readAsDataURL(file);

                    }else{
                        this.product.image = ""
                        this.product.imagePreview = ""
                        swal({
                            text:"Archivo no es imagen o pdf",
                            icon:"error"
                        })

                    }


                },
                createProductPicture(file) {

                    this.file = file
                    this.fileType = file['type'].split('/')[0]
                    this.fileName = file['name']

                    if(this.fileType == "image"){

                        let reader = new FileReader();
                        let vm = this;
                        reader.onload = (e) => {
                            vm.product.productImage = e.target.result;
                        };
                        reader.readAsDataURL(file);

                    }else{
                        this.product.productImage = ""
                        this.product.productImagePreview = ""
                        swal({
                            text:"Archivo no es imagen",
                            icon:"error"
                        })

                    }


                },
                
            },
            created(){

                this.fetchAllResellers();
                this.createdFetchDepartments()

            }

        })
    </script>

@endpush