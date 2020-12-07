@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="logs-dev">
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
                        <h3 class="card-label">Bitácora
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
                                <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Tracking #, warehouse, usuario, cliente">
                            </div>
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
                                        <span style="width: 250px;">Usuario</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Fecha</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Acción</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="log in logs">
                        
                                    <td class="datatable-cell">
                                        <span v-if="log.shipping">@{{ log.shipping.tracking }}</span>
                                    </td>
                                    <td class="datatable-cell">
                                        <span v-if="log.shipping">@{{ log.shipping.warehouse_number }}</span>
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ log.user.name }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ dateFormatter(log.created_at) }} 
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ log.shipping_status.name }}
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

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#logs-dev',
            data() {
                return {
                    query:"",
                    logs:[],
                    pages:0,
                    page:1,
                    loading:false
                }
            },
            methods: {

                fetch(page = 1){
                    
                    this.page = page
                   
                    if(this.query == ""){
                        
                        axios.post("{{ url('/binnacle/fetch') }}", {"page": this.page}).then(res => {
                        
                            this.logs = res.data.logs
                            this.pages = Math.ceil(res.data.logsCount / res.data.dataAmount)
                            //this.setCheckbox()
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
                        
                        axios.post("{{ url('/binnacle/search') }}", {search: this.query, page: this.page}).then(res =>{

                            this.logs = res.data.logs
                            this.pages = Math.ceil(res.data.logsCount / res.data.dataAmount)
                            //this.setCheckbox()
                        })

                    }

                }

            },
            created(){

                this.fetch()
                

            }

        })
    </script>


@endpush