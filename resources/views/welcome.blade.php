@extends('layouts.main')

@section("content")

    <div class="container" id="home-dev">

        <div class="loader-cover-custom" v-if="loading == true">
			<div class="loader-custom"></div>
		</div>

        <div class="row">
            <div class="col-xl-9">

                <!--begin::Mixed Widget 15-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Envíos</h3>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column">
                        
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                                <table class="table">
                                    <thead>
                                        <tr >
                                            <th class="datatable-cell datatable-cell-sort">
                                                <span style="width: 250px;">Tracking #</span>
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
                                            <td class="datatable-cell" v-if="shipping.recipient">
                                                @{{ shipping.recipient.name }}
                                            </td>
                                            <td class="datatable-cell" v-if="shipping.client">
                                                @{{ shipping.client.name }} @{{ shipping.client.lastname }}
                                            </td>
                                            <td class="datatable-cell">
                                                @{{ dateFormatter(shipping.created_at) }}
                                            </td>
                                            <td class="datatable-cell">
                                                @{{ shipping.shipping_status.name }} <span v-if="shipping.address == null"> - Dirección requerida </span>
                                            </td>
                                            <td>
                                                <button v-if="shipping.shipping_status_id < 4" title="Actualizar Status" class="btn btn-success" data-toggle="modal" data-target="#shippingModal" @click="edit(shipping)"><i class="far fa-edit"></i></button>
                                                <a title="Editar" :href="'{{ url('/shippings/show') }}'+'/'+shipping.tracking" class="btn btn-info"><i class="far fa-eye"></i></a>
                                                <a title="Etiqueta" :href="'{{ url('/shippings/qr') }}'+'/'+shipping.id" class="btn btn-info" target="_blank"><i class="far fa-file-pdf"></i></a>
                                                {{--<button class="btn btn-secondary"><i class="far fa-trash-alt"></i></button>--}}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!--end: Datatable-->

                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Mixed Widget 15-->

                </div>

                @if(\Auth::user()->role_id < 3 )
                <div class="col-xl-3">
                    <!--begin::Mixed Widget 15-->
                    <div class="card card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title font-weight-bolder">Estadísticas</h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body d-flex flex-column">
                            
                            <!--begin::Items-->
                            <div class="mt-5">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center mr-2">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                            <div class="symbol-label">
                                                <img src="assets/media/svg/misc/006-plurk.svg" class="h-50" alt="">
                                            </div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div>
                                            <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Envíos</a>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Section-->
                                    <!--begin::Label-->
                                    <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">{{ App\Shipping::count() }}</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Widget Item-->
                                <div class="d-flex align-items-center justify-content-between mb-5">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center mr-2">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                            <div class="symbol-label">
                                                <img src="assets/media/svg/misc/015-telegram.svg" class="h-50" alt="">
                                            </div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div>
                                            <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Destinatarios</a>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Section-->
                                    <!--begin::Label-->
                                    <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">{{ App\Recipient::count() }}</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Widget Item-->
                                <!--begin::Widget Item-->
                                <div class="d-flex align-items-center justify-content-between">
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-center mr-2">
                                        <!--begin::Symbol-->
                                        <div class="symbol symbol-50 symbol-light mr-3 flex-shrink-0">
                                            <div class="symbol-label">
                                                <img src="assets/media/svg/misc/003-puzzle.svg" class="h-50" alt="">
                                            </div>
                                        </div>
                                        <!--end::Symbol-->
                                        <!--begin::Title-->
                                        <div>
                                            <a href="#" class="font-size-h6 text-dark-75 text-hover-primary font-weight-bolder">Usuarios</a>
                                        </div>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Section-->
                                    <!--begin::Label-->
                                    <div class="label label-light label-inline font-weight-bold text-dark-50 py-4 px-3 font-size-base">{{ App\User::count() }}</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Widget Item-->
                            </div>
                            <!--end::Widget Items-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 15-->
                </div>
                @endif
        </div>

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
            
                            <select class="form-control" v-model="status">
                                <option :value="status.id" v-for="status in statuses" v-if="status.id > actualStatusId">@{{ status.name }}</option>
                            </select>
                            <small v-if="errors.hasOwnProperty('status')">@{{ errors['status'][0] }}</small>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold" @click="update()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@push("scripts")

    @if(\Auth::user()->role_id == 4 && (\Auth::user()->dni_picture == null || \Auth::user()->address == null || \Auth::user()->dni == null))
        <script>
            swal({
                title:"Sugerencia",
                text:"Para poder crear envíos debes completar los datos en tu perfil",
                icon: "warning"
            })
        </script>
    @endif

    <script>
        const devArea = new Vue({
            el: '#home-dev',
            data() {
                return {
                    shippings:[],
                    shippingId:"",
                    actualStatus:"",
                    actualStatusId:"",
                    statuses:[],
                    status:"",
                    errors:[],
                    loading:false
                }
            },
            methods: {

                getShippings(){
                    
                    axios.get("{{ url('/shippings/fetch/1') }}").then(res => {
                        
                        this.shippings = res.data.shippings

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
                            }).then(res => {
                                window.location.reload()
                            })
                            
                            
                            //this.getShippings()

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

                }

            },
            created(){

                this.getShippings()
                this.getAllStatuses()

            }

        })
    </script>


@endpush