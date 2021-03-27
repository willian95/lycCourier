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
                        <h3 class="card-label">Guías
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Dropdown-->
                        {{--<div class="dropdown dropdown-inline mr-2">
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
                                    <li class="navi-item">
                                        <a href="#" class="navi-link" data-toggle="modal" data-target="#exportModal" @click="setExportType('pdf')">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Navigation-->
                            </div>
                            <!--end::Dropdown Menu-->
                        </div>--}}
                        <!--end::Dropdown-->
                        <!--begin::Button-->
                        @if(\Auth::user()->role_id == 1 || \Auth::user()->role_id == 2)
                        <a href="{{ url('shipping-guide/create') }}" class="btn btn-primary font-weight-bolder">
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
                        </span>Nueva guía</a>
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
                                <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Guía #, Warehouse #">
                            </div>
                        </div>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                        <table class="table">
                            <thead>
                                <tr >
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Guía #</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Fecha</span>
                                    </th>


                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 130px;">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="guide in guides">

                                    <td class="datatable-cell">
                                        @{{ guide.guide }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ dateFormatter(guide.created_at) }} 
                                    </td>

                                    <td>
                                        
                                        <button title="Envíos" class="btn btn-success" @click="setShippings(guide.shipping_guide_shipping)" data-toggle="modal" data-target="#shippingModal"><i class="fas fa-eye"></i></button>
                                        <a title="Editar" class="btn btn-info" :href="'{{ url('/shipping-guide/edit/') }}'+'/'+guide.id"><i class="fas fa-edit"></i></a>
                                        <button title="Eliminar" class="btn btn-danger" @click="deleteShippingGuide(guide.id)"><i class="far fa-trash-alt"></i></button>

                                        
                                    </td>
                                </tr>
                                

                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="dataTables_info" id="kt_datatable_info" role="status" aria-live="polite">Mostrando página @{{ page }} de @{{ pages }}</div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="dataTables_paginate paging_full_numbers" id="kt_datatable_paginate">
                                    <ul class="pagination">
                                        <li class="paginate_button page-item previous" id="kt_datatable_previous" v-if="page > 1">
                                            <a style="cursor:pointer;" @click="fetch(1)" aria-controls="kt_datatable" data-dt-idx="1" tabindex="0" class="page-link">
                                                <i class="ki ki-arrow-back"></i>
                                            </a>
                                        </li>
                                        <li class="paginate_button page-item active" v-for="index in pages">
                                            {{--<a style="cursor:pointer;" aria-controls="kt_datatable" tabindex="0" class="page-link":key="index" @click="fetch(index)" >@{{ index }}</a>--}}
                                            <a class="page-link" style="background-color: #d32b2b; color: #fff !important; cursor:pointer;" v-if="page == index && index >= page - 3 &&  index < page + 3"  :key="index" @click="fetch(index)" >@{{ index }}</a>
                                            <a class="page-link" style="cursor:pointer;" v-if="page != index && index >= page - 3 &&  index < page + 3"  :key="index" @click="fetch(index)" >@{{ index }}</a> 
                                        </li>
                                        
                                        <li class="paginate_button page-item next" id="kt_datatable_next" v-if="page < pages" href="#">
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" data-dt-idx="7" tabindex="0" class="page-link" @click="fetch(pages)">
                                                <i class="ki ki-arrow-next"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="d-flex">
                                    <label for="">Ir a página</label>
                                    <input type="text" class="form-control w-50" v-model="searchPage" @keypress="isNumber($event)">
                                    <button class="btn btn-success" @click="searchPageAction()">ir</button>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Envíos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tracking</th>
                                    <th>Warehouse #</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="shipping in shippings">
                                    <td>
                                        <a :href="'{{ url('/shippings/show/') }}'+'/'+shipping.id">@{{ shipping.shipping.tracking }}</a>
                                    </td>
                                    <td>@{{ shipping.shipping.warehouse_number }}</td>
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
                    guides:[],
                    query:"",
                    errors:[],
                    pages:0,
                    page:1,
                    startDateExport:"",
                    endDateExport:"",
                    exportType:"",
                    loading:false,
                    searchPage:1,
                    shippings:[]
                }
            },
            methods: {

                searchPageAction(){

                    this.fetch(parseInt(this.searchPage))

                },
                fetch(page = 1){
                    
                    this.page = page
                   
                    if(this.query == ""){
                        
                        axios.get("{{ url('/shipping-guide/fetch/') }}"+"/"+page).then(res => {
                        
                            this.guides = res.data.shippingGuides
                            this.pages = Math.ceil(res.data.shippingGuidesCount / res.data.dataAmount)
        
                        })
                    }else{

                        this.search()

                    }

                    

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
                        
                        axios.post("{{ url('/shipping-guide/search') }}", {search: this.query, page: this.page}).then(res =>{

                            this.guides = res.data.shippingGuides
                            this.pages = Math.ceil(res.data.shippingGuidesCount / res.data.dataAmount)
                            //this.setCheckbox()
                        })

                    }

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

                deleteShippingGuide(id){

                    swal({
                        title: "¿Estás seguro?",
                        text: "Eliminarás esta guía!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            this.loadgin = true
                            axios.post("{{ url('/shipping-guide/delete') }}", {"id": id}).then(res => {
                                this.loadgin = false
                                if(res.data.success == true){

                                    swal({
                                        "text": res.data.msg,
                                        "icon": "success"
                                    })

                                    this.fetch()

                                }else{

                                    swal({
                                        "text": res.data.msg,
                                        "icon": "error"
                                    })

                                }

                            })

                        }
                    })

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
                setShippings(shippings){

                    this.shippings = shippings

                }

            },
            created(){

                this.fetch()

            }

        })
    </script>


@endpush