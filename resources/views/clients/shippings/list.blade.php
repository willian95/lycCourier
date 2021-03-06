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
                        @if(\Auth::user()->dni_picture != null)
                        <a href="{{ route('client.shippings.create') }}" class="btn btn-primary font-weight-bolder">
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
                                    <td class="datatable-cell" v-if="shipping.client">
                                        @{{ shipping.client.name }}
                                    </td>
                                    <td class="datatable-cell" v-if="shipping.shipped_at">
                                        @{{ dateFormatter(shipping.shipped_at) }} 
                                    </td>
                                    <td class="datatable-cell" v-else>
                                        Aún no enviado
                                    </td>
                                    <td class="datatable-cell" v-if="shipping.shipping_status_id == 1 && shipping.shipped_at != ''">
                                        Envío aún no procesado
                                    </td>
                                    <td class="datatable-cell" v-else>
                                        @{{ shipping.shipping_status.name }}
                                    </td>
                                    <td>

                                        <a title="Editar" v-if="shipping.is_finished == 0" :href="'{{ url('clients/shipping/') }}'+'/'+shipping.tracking" class="btn btn-info"><i class="far fa-eye"></i></a>
                                        <button title="Listado de actualizaciones" v-if="selectedShippings.length == 0" class="btn btn-info" data-toggle="modal" data-target="#shippingHistoryModal" @click="setShippingHistory(shipping.shipping_histories)"><i class="far fa-list-alt"></i></button>
                                        
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
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="history in histories">
                                    <td v-if="history.shipping_status">@{{ history.shipping_status.name }}</td>
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
                    shippings:[],
                    selectedShippings:[],
                    statuses:[],
                    status:"",
                    query:"",
                    errors:[],
                    pages:0,
                    page:1,
                    histories:"",
                    loading:false
                }
            },
            methods: {

                fetch(page = 1){
                    
                    this.page = page
                   
                    if(this.query == ""){
                        
                        axios.get("{{ url('clients/shipping/fetch/') }}"+"/"+page).then(res => {
                        
                            this.shippings = res.data.shippings
                            this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)
                        })
                    }else{

                        this.search()

                    }

                    

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
                search(){
                    
                    
                    if(this.query == ""){
                        
                        this.fetch()

                    }else{
                        
                        axios.post("{{ url('/clients/shipping/search') }}", {search: this.query, page: this.page}).then(res =>{

                            this.shippings = res.data.shippings
                            this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)
                            //this.setCheckbox()
                        })

                    }

                },

            },
            created(){

                this.fetch()
                this.getAllStatuses()

            }

        })
    </script>


@endpush