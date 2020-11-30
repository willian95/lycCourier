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
                        
                    </div>
                </div>
                <!--end::Header-->

                

                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Búsqueda</label>
                            <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Tracking #">
                        </div>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                        <table class="table">
                            <thead>
                                <tr >
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
                                        @{{ dateFormatter(shipping.created_at) }}
                                    </td>
                                    <td class="datatable-cell" v-if="shipping.shipping_status">
                                        @{{ shipping.shipping_status.name }} <span v-if="shipping.address == null"> - Dirección requerida </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-success" data-toggle="modal" data-target="#shippingModal" @click="edit(shipping)"><i class="far fa-edit"></i></button>
                                        <a :href="'{{ url('/shippings/show') }}'+'/'+shipping.tracking" class="btn btn-info"><i class="far fa-eye"></i></a>
                                        <a :href="'{{ url('/shippings/qr') }}'+'/'+shipping.id" class="btn btn-info" target="_blank"><i class="far fa-file-pdf"></i></a>
                                        <button class="btn btn-info" data-toggle="modal" data-target="#shippingHistoryModal" @click="setShippingHistory(shipping.shipping_histories)"><i class="far fa-list-alt"></i></button>
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
                                        <li class="paginate_button page-item previous disabled" id="kt_datatable_previous" v-if="page > 1">
                                            <a href="#" aria-controls="kt_datatable" data-dt-idx="1" tabindex="0" class="page-link">
                                                <i class="ki ki-arrow-back"></i>
                                            </a>
                                        </li>
                                        <li class="paginate_button page-item active" v-for="index in pages">
                                            <a href="#" aria-controls="kt_datatable" tabindex="0" class="page-link":key="index" @click="fetch(index)" >@{{ index }}</a>
                                        </li>
                                        
                                        <li class="paginate_button page-item next" id="kt_datatable_next" v-if="page < pages" href="#">
                                            <a href="#" aria-controls="kt_datatable" data-dt-idx="7" tabindex="0" class="page-link" @click="fetch(page + 6)">
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

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#shippings-dev',
            data() {
                return {
                    recipientId:"{{ $recipient }}",
                    shippings:[],
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
                    loading:false
                }
            },
            methods: {

                fetch(page = 1){
                    
                    this.page = page

                    axios.get("{{ url('/recipients/shipping/') }}"+"/"+this.recipientId+"/fetch"+"/"+page).then(res => {
                        
                        this.shippings = res.data.shippings
                        this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)

                    })

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

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });

                            $("#shippingModalClose").click();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '0px');
                            $('.modal-backdrop').remove();
                           
                            this.fetch()

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
                        
                        axios.post("{{ url('recipients/shippings/search') }}", {search: this.query, recipient: this.recipientId}).then(res =>{

                            this.shippings = res.data.shippings

                        })

                    }

                },
                
                setShippingHistory(history){

                    this.histories = history

                }

            },
            created(){

                this.fetch()
                this.getAllStatuses()

            }

        })
    </script>


@endpush