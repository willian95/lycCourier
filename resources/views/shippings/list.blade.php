@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="shippings-dev">
        <!--begin::Container-->
        <div class="container">

            <div class="loader-cover-custom" v-if="loading == true">
                <div class="loader-custom"></div>
            </div>

            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Envíos
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click="toggleList()">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3"></path>
                                        <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Exportar</button>
                            <!--begin::Dropdown Menu-->
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" id="export-list">
                                <!--begin::Navigation-->
                                <ul class="navi flex-column navi-hover py-2">
                                    
                                    <li class="navi-item">
                                        <a href="#" class="navi-link" data-toggle="modal" data-target="#exportModal" @click="setExportType('excel')">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    {{--<li class="navi-item">
                                        <a href="#" class="navi-link" data-toggle="modal" data-target="#exportModal" @click="setExportType('pdf')">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>--}}
                                </ul>
                                <!--end::Navigation-->
                            </div>
                            <!--end::Dropdown Menu-->
                        </div>
                        <!--end::Dropdown-->
                        <!--begin::Button-->
                        @if(\Auth::user()->role_id == 1)
                        <a href="{{ route('shippings.create') }}" class="btn btn-primary font-weight-bolder">
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
                        </span>Nuevo Envío</a>
                        @endif
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Header-->

                

                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Búsqueda</label>
                                <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Tracking #">
                            </div>
                        </div>
                        <div class="col-md-8" v-if="selectedShippings.length > 0">
                            <p class="text-right">
                                <button class="btn btn-success" data-toggle="modal" data-target="#massShippingModal">Enviar</button>
                            </p>
                        </div>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                        <table class="table">
                            <thead>
                                <tr >
                                    <th class="datatable-cell datatable-cell-sort">
                                        <input type="checkbox" class="form-check-input" @click="selectAllShippings()" style="margin-top: -16px;">
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Tracking #</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Warehouse #</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Destinatario</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Fecha de creación</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Status</span>
                                    </th>

                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 130px;">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="shipping in shippings">
                                    <td>
                                        <input type="checkbox" class="form-check-input" @click="selectShipping(shipping)" :checked="checkTest(shipping)">
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ shipping.tracking }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ shipping.warehouse_number }}
                                    </td>
                                    <td class="datatable-cell" v-if="shipping.recipient">
                                        @{{ shipping.recipient.name }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ dateFormatter(shipping.shipped_at) }} 
                                    </td>
                                    <td class="datatable-cell" v-if="shipping.shipping_status">
                                        @{{ shipping.shipping_status.name }} <span v-if="shipping.address == null"> - Dirección requerida </span>
                                    </td>
                                    <td>
                                        
                                        <button v-if="selectedShippings.length == 0" class="btn btn-success" data-toggle="modal" data-target="#shippingModal" @click="edit(shipping)" v-if="shipping.shipping_status_id < 5" ><i class="far fa-edit"></i></button>
                                        <a v-if="selectedShippings.length == 0" :href="'{{ url('/shippings/show') }}'+'/'+shipping.tracking" class="btn btn-info"><i class="far fa-eye"></i></a>
                                        <a v-if="selectedShippings.length == 0" :href="'{{ url('/shippings/qr') }}'+'/'+shipping.id" class="btn btn-info" target="_blank"><i class="far fa-file-pdf"></i></a>
                                        <button v-if="selectedShippings.length == 0" class="btn btn-info" data-toggle="modal" data-target="#shippingHistoryModal" @click="setShippingHistory(shipping.shipping_histories)"><i class="far fa-list-alt"></i></button>
                                        {{--<button class="btn btn-secondary"><i class="far fa-trash-alt"></i></button>--}}
                                    </td>
                                </tr>
                                

                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="kt_datatable_info" role="status" aria-live="polite">Mostrando página @{{ page }} de @{{ pages }}</div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_full_numbers" id="kt_datatable_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous" id="kt_datatable_previous" v-if="page > 1">
                                            <a style="cursor:pointer;" @click="fetch(1)" aria-controls="kt_datatable" data-dt-idx="1" tabindex="0" class="page-link">
                                                <i class="ki ki-arrow-back"></i>
                                            </a>
                                        </li>
                                        <li class="paginate_button page-item active" v-for="index in pages">
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" tabindex="0" class="page-link":key="index" @click="fetch(index)" >@{{ index }}</a>
                                        </li>
                                        
                                        <li class="paginate_button page-item next" id="kt_datatable_next" v-if="page < pages" href="#">
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" data-dt-idx="7" tabindex="0" class="page-link" @click="fetch(pages)">
                                                <i class="ki ki-arrow-next"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        <!-- Modal-->
        <div class="modal fade" id="shippingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar envío</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Status actual: @{{ actualStatus }}</p>
                        <div class="form-group">
                            <label for="name">Status</label>
                            <select class="form-control"  v-model="status">
                                <option :value="status.id" v-for="status in statuses" v-if="status.id > actualStatusId">@{{ status.name }}</option>
                            </select>
                            <small v-if="errors.hasOwnProperty('status')">@{{ errors['status'][0] }}</small>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="shippingModalClose" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold" @click="update()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal-->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Elegir fechas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Inicio</label>
                                    <input type="date" class="form-control" v-model="startDateExport">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fin</label>
                                    <input type="date" class="form-control" v-model="endDateExport">
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="shippingModalClose" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold" @click="exportData()">Exportar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal-->
        <div class="modal fade" id="shippingHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Historial</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="history in histories">
                                    <td v-if="history.shipping_status">@{{ history.shipping_status.name }}</td>
                                    <td v-if="history.user">@{{ history.user.name }}</td>
                                    <td>@{{ dateFormatter(history.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="shippingModalClose" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal-->
        <div class="modal fade" id="massShippingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Status</label>
                            <select class="form-control"  v-model="status">
                                <option :value="status.id" v-for="status in statuses">@{{ status.name }}</option>
                            </select>
                            <small v-if="errors.hasOwnProperty('status')">@{{ errors['status'][0] }}</small>
                        </div>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tracking #</th>
                                    <th>Warehouse #</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="selectedShip in selectedShippings">
                                    <td>@{{ selectedShip.tracking }}</td>
                                    <td>@{{ selectedShip.warehouse_number }}</td>
                                    <td>@{{ selectedShip.shipping_status.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="shippingModalClose" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold" @click="massUpdate()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#shippings-dev',
            data() {
                return {
                    shippings:[],
                    selectedShippings:[],
                    shippingId:"",
                    actualStatus:"",
                    actualStatusId:"",
                    statuses:[],
                    status:"",
                    query:"",
                    errors:[],
                    pages:0,
                    page:1,
                    startDateExport:"",
                    endDateExport:"",
                    exportType:"",
                    histories:"",
                    selectedAll:false,
                    loading:false
                }
            },
            methods: {

                fetch(page = 1){
                    
                    this.page = page
                   
                    if(this.query == ""){
                        
                        axios.get("{{ url('/shippings/fetch/') }}"+"/"+page).then(res => {
                        
                            this.shippings = res.data.shippings
                            this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)
                            //this.setCheckbox()
                        })
                    }else{

                        this.search()

                    }

                    

                },
                edit(shipping){
                    
                    this.shippingId = shipping.id
                    this.actualStatus = shipping.shipping_status.name
                    this.actualStatusId = shipping.shipping_status.id
                },
                getAllStatuses(){
                    axios.get("{{ url('/shippings/statuses') }}").then(res => {
                        
                        this.statuses = res.data.statuses

                    })
                },
                dateFormatter(date){
                    
                    let year = date.substring(0, 4)
                    let month = date.substring(5, 7)
                    let day = date.substring(8, 10)
                    return day+"-"+month+"-"+year
                },
                update(){

                    this.loading = true
                    axios.post("{{ url('/shippings/update') }}", {id: this.shippingId, status: this.status}).then(res => {

                        this.loading = false
                        if(res.data.success == true){

                            $("#shippingModalClose").click();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '0px');
                            $('.modal-backdrop').remove();

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            }).then(res => {
                                window.location.reload()
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
                        this.loading = false
                        this.errors = err.response.data.errors
                    })

                },
                search(){
                    
                    
                    if(this.query == ""){
                        
                        this.fetch()

                    }else{
                        
                        axios.post("{{ url('/shippings/search') }}", {search: this.query, page: this.page}).then(res =>{

                            this.shippings = res.data.shippings
                            this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)
                            //this.setCheckbox()
                        })

                    }

                },
                checkTest(shipping){
                    var exists = false
                    this.selectedShippings.forEach((data) => {
                        if(data.id == shipping.id){
                            exists = true
                        }
                    })

                    return exists
                },
                toggleList(){

                    if($("#export-list").hasClass("show")){
                        $("#export-list").removeClass("show")
                    }else{
                        $("#export-list").addClass("show")
                    }

                },
                setExportType(type){
                    this.toggleList()
                    this.exportType = type
                },
                exportData(){

                    if(this.exportType == 'excel'){

                        window.open("{{ url('/shippings/export/excel/') }}"+"/"+this.startDateExport+"/"+this.endDateExport)

                    }else{

                        window.open("{{ url('/shippings/export/pdf/') }}"+"/"+this.startDateExport+"/"+this.endDateExport)

                    }

                },
                selectShipping(shipping){
                                        
                    var exists = false
                       
                    this.selectedShippings.forEach((data, index) => {

                        if(data.id == shipping.id){
                            exists = true
                            this.selectedShippings.splice(index, 1)
                        }

                    })

                    if(exists == false){
                        this.selectedShippings.push(shipping)
                    }
                        
                    

                },
                selectAllShippings(){
                    
                    this.selectedShippings = []
                    
                    if(this.selectedAll == false){

                        this.shippings.forEach((data) => {

                            this.selectedShippings.push(data)

                        })
                        this.selectedAll = true

                    }else{
                        this.selectedAll = false
                        
                    }

                },
                setCheckbox(){
                    
                   
                    $(".form-check-input").prop( "checked", false );
                    this.selectedShippings.forEach((data) => {
                    
                        $("#shipping"+data.id).prop( "checked", true );

                    })
                    

                },
                setShippingHistory(history){

                    this.histories = history

                },
                massUpdate(){

                    if(this.status == ""){
                        swal({
                            text: "Debe indicar un status para poder enviar",
                            icon: "success"
                        })
                    }else{
                        this.loading = true
                        axios.post("{{ url('shippings/mass/update') }}", {"selectedShippings": this.selectedShippings,"status": this.status}).then(res => {
                            this.loading = false
                            if(res.data.success == true){

                                swal({
                                    title: "Excelente",
                                    text: res.data.msg,
                                    icon: "success"
                                }).then(res => {

                                    window.location.reload()

                                })

                            }

                        })
                    }

                }

            },
            created(){

                this.fetch()
                this.getAllStatuses()

            }

        })
    </script>


@endpush