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
                        <h3 class="card-label">Crear guía<h3>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                   <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-center">Ingresa el número de guía</h4>
                            </div>
                            <div class="col-md-6 offset-md-3">
                                <div class="form-group">
                                    <input class="form-control" v-model="guide">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('guide')">@{{ errors['guide'][0] }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-center">Selecciona los envíos que desees agregar a esta guía</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Búsqueda</label>
                                    <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Tracking #">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('shippings')">@{{ errors['shippings'][0] }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            

                            <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                                <table class="table">
                                    <thead>
                                        <tr >
                                            <th class="datatable-cell datatable-cell-sort">
                                                
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
                                                <span style="width: 250px;">Fecha de envío</span>
                                            </th>
                                            <th class="datatable-cell datatable-cell-sort">
                                                <span style="width: 250px;">Status</span>
                                            </th>

                                            <th class="datatable-cell datatable-cell-sort">
                                                <span style="width: 250px;">Tipo de empaque</span>
                                            </th>

                                            <th class="datatable-cell datatable-cell-sort">
                                                <span style="width: 250px;">Número de guía</span>
                                            </th>

                                            

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="shipping in shippings" v-if="shipping.tracking">
                                            <td >
                                                <input v-if="!shipping.shipping_guide" type="checkbox" class="form-check-input" @click="selectShipping(shipping)" :checked="checkTest(shipping)">
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
                                            <td class="datatable-cell" v-if="shipping.client">
                                                @{{ shipping.client.name }}
                                            </td>
                                            <td class="datatable-cell" v-if="shipping.shipped_at">
                                                @{{ dateFormatter(shipping.shipped_at) }} 
                                            </td>
                                            <td class="datatable-cell" v-else>
                                                Aún no enviado
                                            </td>
                                            <td class="datatable-cell" v-if="shipping.shipping_status_id == 1 && shipping.shipped_at == null">
                                                Envío aún no procesado
                                            </td>
                                            <td class="datatable-cell" v-else>
                                                @{{ shipping.shipping_status.name }} <span v-if="shipping.address == null"> - Dirección requerida </span>
                                            </td>
                                            <td class="datatable-cell">
                                                <span v-if="shipping.box">
                                                    @{{ shipping.box.name }}
                                                </span>
                                            </td>
                                            <td class="datatable-cell">
                                                <span v-if="shipping.shipping_guide">
                                                    @{{ shipping.shipping_guide.guide }}
                                                </span>
                                                <span v-else>
                                                    No posee
                                                </span>
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

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#shipping-dev',
            data() {
                return {
                    shippings:[],
                    selectedShippings:[],
                    errors:[],
                    guide:"",
                    loading:false,
                    query:"",
                    pages:0,
                    page:1,
                    searchPage:1
                }
            },
            methods: {

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
                checkTest(shipping){
                    var exists = false
                    this.selectedShippings.forEach((data) => {
                        if(data.id == shipping.id){
                            exists = true
                        }
                    })

                    return exists
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
                dateFormatter(date){
                    
                    let year = date.substring(0, 4)
                    let month = date.substring(5, 7)
                    let day = date.substring(8, 10)
                    return day+"-"+month+"-"+year
                },
                searchPageAction(){

                    this.fetch(parseInt(this.searchPage))

                    },
                    fetch(page = 1){

                    this.page = page

                    if(this.query == ""){
                        
                        axios.get("{{ url('/shippings/fetch/') }}"+"/"+page).then(res => {
                        
                            this.shippings = res.data.shippings
                            this.pages = Math.ceil(res.data.shippingsCount / res.data.dataAmount)

                        })
                    }else{

                        this.search()

                    }

                },
                async store(){

                    try{

                        let shippingsId = this.getOnlyShippingsId()

                        this.errors = []
                        this.loading = true
                        let response = await axios.post("{{ url('shipping-guide/store') }}", {guide: this.guide, shippings: shippingsId})
                        this.loading = false

                        if(response.data.success){

                            swal({
                                text: response.data.msg,
                                icon:"success"
                            }).then(res => {
                                window.location.href="{{ url('/shipping-guide') }}"
                            })

                        }else{

                            swal({
                                text: response.data.msg,
                                icon:"danger"
                            })

                        }


                    }catch(err){
                        alertify.error("Hay algunos campos que debe revisar")

                        this.loading = false
                        this.errors = err.response.data.errors

                    }
  

                },
                getOnlyShippingsId(){

                    return this.selectedShippings.map(data => {return data.id})

                }

            },
            created(){

                this.fetch()

            }

        })
    </script>


@endpush