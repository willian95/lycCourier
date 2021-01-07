@extends("layouts.main")

@section("content")

    <style>
        small{
            color: red;
        }
    </style>

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
                        <h3 class="card-label">Crear envío<h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                   <div class="container-fluid">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <h3 class="text-center">Detalles del envío</h3>
                            </div>
                            <div class="col-12">
                                <h3>Warehouse #: {{ "WRI".str_pad(DB::select("SHOW TABLE STATUS LIKE 'shippings'")[0]->Auto_increment, 10, "0", STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient">Destinatario</label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" @click="showRecipientSearch()" id="recipient" autocomplete="off" v-model="recipientShowName">
                                        <button class="btn btn-success" data-toggle="modal" data-target="#recipientModal"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <small v-if="errors.hasOwnProperty('recipientId')">@{{ errors['recipientId'][0] }}</small>
                                </div>
                               
                            </div>
                            
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tracking">Tracking number</label>
                                    <input type="text" class="form-control" v-model="tracking">
                                    <small v-if="errors.hasOwnProperty('tracking')">@{{ errors['tracking'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient">Imagen DNI</label>
                                    <input type="file" class="form-control" @change="onImageChange" style="overflow:hidden;">
                                    <img :src="imagePreview" style="width: 40%" />
                                </div>
                               
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
                                    <label for="address">Dirección</label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" id="address" autocomplete="off" v-model="address">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Reseller</label>
                                    <select class="form-control" v-model="resellerId">
                                        <option value="">Sin reseller</option>
                                        <option :value="reseller.id" v-for="reseller in resellers">@{{ reseller.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h3 class="text-center">Detalles del paquete</h3>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <textarea rows="4" id="description" class="form-control" v-model="description"></textarea>
                                    <small v-if="errors.hasOwnProperty('description')">@{{ errors['description'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="agent">Piezas</label>
                                    <input type="text" class="form-control" @keypress="isNumber($event)" v-model="pieces">
                                    <small v-if="errors.hasOwnProperty('pieces')">@{{ errors['pieces'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="recipient">Tipo de paquete</label>
                                    <div style="display: flex;">
                                        <input type="text" class="form-control" v-model="packageShowName" @click="showPackageSearch()"><button class="btn btn-success" data-toggle="modal" data-target="#packageModal"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <small v-if="errors.hasOwnProperty('packageId')">@{{ errors['packageId'][0] }}</small>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Largo (cm)</label>
                                    <input type="text" class="form-control" @keypress="isNumberDot($event)" v-model="length">
                                    <small v-if="errors.hasOwnProperty('length')">@{{ errors['length'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Alto (cm)</label>
                                    <input type="text" class="form-control" @keypress="isNumberDot($event)" v-model="height">
                                    <small v-if="errors.hasOwnProperty('height')">@{{ errors['height'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Ancho (cm)</label>
                                    <input type="text" class="form-control" @keypress="isNumberDot($event)" v-model="width">
                                    <small v-if="errors.hasOwnProperty('width')">@{{ errors['width'][0] }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Peso (kg)</label>
                                    <input type="text" class="form-control" @keypress="isNumberDot($event)" v-model="weight">
                                    <small v-if="errors.hasOwnProperty('weight')">@{{ errors['weight'][0] }}</small>
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
                                            <td><img :src="product.imagePreview" alt="" style="width: 70%;"></td>
                                            <td>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#productModal" @click="edit(product, index)"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-secondary" @click="erase(index)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12">

                                <p class="text-center">
                                    <button class="btn btn-success" @click="store()">Crear</button>
                                </p>

                            </div>
                        </div>
                   </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

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
                            <small v-if="recipientErrors.hasOwnProperty('name')">@{{ recipientErrors['name'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="recipientEmail">Email</label>
                            <input type="text" class="form-control" id="recipientEmail" v-model="recipientEmail">
                            <small v-if="recipientErrors.hasOwnProperty('email')">@{{ recipientErrors['email'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="recipientPhone">Teléfono</label>
                            <input type="text" class="form-control" id="recipientPhone" v-model="recipientPhone">
                            <small v-if="recipientErrors.hasOwnProperty('phone')">@{{ recipientErrors['phone'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="recipientAddress">Dirección</label>
                            <input type="text" class="form-control" id="recipientAddress" v-model="recipientAddress">
                            <small v-if="recipientErrors.hasOwnProperty('address')">@{{ recipientErrors['address'][0] }}</small>
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
                            <small v-if="packageErrors.hasOwnProperty('name')">@{{ packageErrors['name'][0] }}</small>
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
                            <li class="list-group-item list-group-item-action" style="cursor:pointer;" v-for="recipient in recipients" @click="selectRecipientId(recipient)">@{{ recipient.name }}</li>
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
                                    <label for="name">Descripción</label>
                                    <textarea class="form-control" rows="4" v-model="product.description"></textarea>
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

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#shipping-dev',
            data() {
                return {
                    recipients:[],
                    recipientId:"",
                    recipientQuery:"",
                    recipientShowName:"",
                    packages:[],
                    packageId:"",
                    packageQuery:"",
                    packageShowName:"",
                    tracking:"",
                    description:"",
                    pieces:"",
                    length:"",
                    height:"",
                    width:"",
                    weight:"",
                    recipientName:"",
                    recipientEmail:"",
                    recipientPhone:"",
                    recipientAddress:"",
                    packageName:"",
                    errors:[],
                    resellerId:"",
                    resellers:[],
                    recipientErrors:[],
                    packageErrors:[],
                    address:"",
                    loading:false,
                    image:"",
                    imagePreview:"",
                    products:[],
                    action:"create",
                    product:{
                        name:"",
                        price:"",
                        image:"",
                        imagePreview:"",
                        description:""
                    },
                    departments:[],
                    department:"",
                    provinces:[],
                    province:"",
                    districts:[],
                    district:"",
                }
            },
            methods: {

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
                    this.imagePreview = recipient.dni_picture
                    this.recipients = []
                    this.department = recipient.department_id
                    this.province = recipient.province_id
                    this.district = recipient.district_id

                    this.createdFetchDeparments()

                    $("#recipientModalSearch").click();
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '0px');
                    $('.modal-backdrop').remove();
                   

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
                store(){

                    if(this.imagePreview == "" || this.imagePreview == null){
                        swal({
                            text:"Debes agregar una copia del DNI del destinatario",
                            icon:"error"
                        })
                    }

                    else if(this.products.length <= 0){
                        swal({
                            text:"Debes agregar productos a tu envío",
                            icon:"error"
                        })
                    }

                    else{

                        this.loading = true
                        axios.post("{{ url('shippings/store') }}", {recipientId: this.recipientId, packageId: this.packageId, tracking: this.tracking, description: this.description, pieces: this.pieces, length: this.length, height: this.height, width: this.width, weight: this.weight, address: this.address, resellerId: this.resellerId, dniPicture: this.image, products: this.products, department: this.department, province: this.province, district: this.district})
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

                    

                },
                fetchResellers(){

                    axios.get("{{ url('/resellers/fetch') }}").then(res => {

                        this.resellers = res.data.resellers

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
                    this.image = e.target.files[0];

                    this.imagePreview = URL.createObjectURL(this.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createImage(files[0]);
                },
                createImage(file) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = (e) => {
                        vm.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                onImageProductChange(e){
                    this.product.image = e.target.files[0];

                    this.product.imagePreview = URL.createObjectURL(this.product.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createProductImage(files[0]);
                },
                createProductImage(file) {
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
                erase(index){
                    this.products.splice(index, 1)
                    swal({
                        title:"¡Genial!",
                        text:"Producto eliminado",
                        icon:"success"
                    })
                },
                fetchDepartments(){
                    
                    
                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments

                    })

                },
                fetchProvinces(){
                    this.province = ""
                    this.district = ""
                    axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                        this.provinces = res.data.provinces

                    })

                },
                fetchDistricts(){

                    axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                        this.districts = res.data.districts

                    })

                },
                createdFetchDeparments(){
                    
                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments
                        axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                            this.provinces = res.data.provinces
                            axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                                this.districts = res.data.districts

                            })
                        })
                    })

                }
            },
            created(){

                this.fetchResellers()
                this.fetchDepartments()


            }

        })
    </script>


@endpush