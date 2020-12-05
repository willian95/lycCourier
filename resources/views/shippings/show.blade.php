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
                            @if(\Auth::user()->role_id == 1)
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
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="recipient">Destinatario</label>
                                        <input type="text" class="form-control" readonly v-model="recipientShowName">
                                    </div>
                                </div>
                            @endif
                            @if(\Auth::user()->role_id == 1)
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
                                        <input type="text" class="form-control" readonly v-model="tracking">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="recipient">Dirección</label>
                                    <input type="text" class="form-control" v-model="address">
                                </div>
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
                                    <input type="text" class="form-control" v-model="pieces">
                                </div>
                            </div>
                            @if(\Auth::user()->role_id == 1)

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="recipient">Tipo de paquete</label>
                                        <div style="display: flex;">
                                            <input type="text" class="form-control" v-model="packageShowName" @click="showPackageSearch()"><button class="btn btn-success" data-toggle="modal" data-target="#packageModal"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <small v-if="errors.hasOwnProperty('packageId')">@{{ errors['packageId'][0] }}</small>

                                    </div>
                                </div>

                            @else

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="recipient">Tipo de paquete</label>
                                        <input type="text" class="form-control" v-model="packageShowName" readonly>
                                    </div>
                                </div>

                            @endif
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Largo (cm)</label>
                                    <input type="text" class="form-control" v-model="length">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Alto (cm)</label>
                                    <input type="text" class="form-control" v-model="height">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Ancho (cm)</label>
                                    <input type="text" class="form-control" v-model="width">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tracking">Peso (kg)</label>
                                    <input type="text" class="form-control" v-model="weight">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <p class="text-center">
                                    <button class="btn btn-primary" @click="updateInfo()">Actualizar</button>
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
                    recipientId:"{{ $shipping->recipient->id }}",
                    recipientQuery:"",
                    recipientShowName:"{{ $shipping->recipient->name }}",
                    packages:[],
                    packageId:"{{ $shipping->box->id }}",
                    packageQuery:"",
                    packageShowName:"{{ $shipping->box->name }}",
                    tracking:"{{ $shipping->tracking }}",
                    description:"{{ $shipping->description }}",
                    pieces:"{{ $shipping->pieces }}",
                    length:"{{ $shipping->length }}",
                    height:"{{ $shipping->height }}",
                    width:"{{ $shipping->width }}",
                    weight:"{{ $shipping->weight }}",
                    recipientName:"",
                    recipientEmail:"",
                    recipientPhone:"",
                    recipientAddress:"",
                    packageName:"",
                    errors:[],
                    recipientErrors:[],
                    packageErrors:[],
                    address:"{{ $shipping->address }}",
                    loading:false
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
                    this.recipients = []

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
                updateInfo(){

                    this.loading = true
                    axios.post("{{ url('shippings/update-info') }}", {shippingId: this.shippingId, recipientId: this.recipientId, packageId: this.packageId, tracking: this.tracking, description: this.description, pieces: this.pieces, length: this.length, height: this.height, width: this.width, weight: this.weight, address: this.address})
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

                }
                
            },
            created(){

                

            }

        })
    </script>

@endpush