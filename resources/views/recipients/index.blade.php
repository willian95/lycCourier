@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="recipient-dev">

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
                        <h3 class="card-label">Destinatarios
                    </div>
                    <div class="card-toolbar">
             
                        <!--begin::Button-->
                        <a href="{{ url('/reseller/recipient/create') }}" class="btn btn-primary font-weight-bolder">
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
                        </span>Nuevo Destinatario</a>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Búsqueda</label>
                            <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Nombre o email">
                        </div>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                        <table class="table">
                            <thead>
                                <tr >
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Nombre</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Email</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Teléfono</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Dirección</span>
                                    </th>

                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 130px;">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="recipient in recipients">
                                    <td class="datatable-cell">
                                        @{{ recipient.name }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ recipient.email }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ recipient.phone }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ recipient.address }}
                                    </td>
                                    <td>
                                        {{--<button class="btn btn-info" data-toggle="modal" data-target="#recipientModal" @click="edit(recipient)"><i class="far fa-edit"></i></button>--}}
                                        <a  class="btn btn-info" :href="'{{ url('/reseller/recipient/edit/') }}'+'/'+recipient.id"><i class="far fa-edit"></i></a>
                                        <a class="btn btn-info" :href="'{{ url('/recipients/shipping')}}'+'/'+recipient.id"><i class="menu-icon flaticon2-telegram-logo"></i></a>
                                        <button class="btn btn-secondary" @click="erase(recipient.id)"><i class="far fa-trash-alt"></i></button>
                                        <a :href="'{{ url('/recipients/profile') }}'+'/'+recipient.id" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        
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
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" data-dt-idx="1" tabindex="0" class="page-link">
                                                <i class="ki ki-arrow-back"></i>
                                            </a>
                                        </li>
                                        <li class="paginate_button page-item active" v-for="index in pages">
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" tabindex="0" class="page-link":key="index" @click="fetch(index)" >@{{ index }}</a>
                                        </li>
                                        
                                        <li class="paginate_button page-item next" id="kt_datatable_next" v-if="page < pages" href="#">
                                            <a style="cursor:pointer;" aria-controls="kt_datatable" data-dt-idx="7" tabindex="0" class="page-link" @click="fetch(page + 6)">
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
            el: '#recipient-dev',
            data() {
                return {
                    recipientId:"",
                    modalTitle:"Nuevo destinatatio",
                    action:"create",
                    recipients:[],
                    errors:[],
                    name:"",
                    email: "",
                    phone: "",
                    address:"",
                    pages:0,
                    page:1,
                    query:"",
                    loading:false
                }
            },
            methods: {

                create(){
                    this.action = "create"
                    this.recipientId = ""
                    this.name = ""
                    this.email = ""
                    this.phone = ""
                    this.address = ""
                },
                search(){

                    if(this.query != ""){
                        axios.post("{{ url('/recipients/list/search') }}", {search: this.query, page: this.page})
                        .then(res => {
                        
                            if(res.data.success == true){

                                this.recipients = res.data.recipients
                                this.pages = Math.ceil(res.data.recipientsCount / res.data.dataAmount)

                            }

                        })
                    }else{
                        this.fetch()
                    }

                    

                },
                store(){

                    this.loading = true
                    axios.post("{{ url('recipients/store') }}", {name: this.name, email: this.email, phone: this.phone, address: this.address})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });
                            this.name = ""
                            this.email = ""
                            this.phone = ""
                            this.address = ""
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
                update(){

                    this.loading = true
                    axios.post("{{ url('recipients/update') }}", {name: this.name, email: this.email, phone: this.phone, address: this.address,id: this.recipientId})
                    .then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });
                            this.name = ""
                            this.email = ""
                            this.phone = ""
                            this.address = ""
                            this.recipientId = ""
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
                edit(recipient){
                    this.modalTitle = "Editar destinatario"
                    this.action = "edit"
                    this.name = recipient.name
                    this.recipientId = recipient.id
                    this.email = recipient.email
                    this.phone = recipient.phone
                    this.address = recipient.address
                },
                fetch(page = 1){

                    this.page = page

                    if(this.query == ""){
                        axios.get("{{ url('recipients/fetch') }}"+"/"+page)
                        .then(res => {

                            this.recipients = res.data.recipients
                            this.pages = Math.ceil(res.data.recipientsCount / res.data.dataAmount)

                        })
                    }else{

                        this.search()

                    }

                },
                erase(id){

                    swal({
                        title: "¿Estás seguro?",
                        text: "Eliminarás este destinatario!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {

                            axios.post("{{ url('recipients/erase') }}", {id: id})
                            .then(res => {

                                if(res.data.success == true){

                                    swal({
                                        title: "Perfecto!",
                                        text: res.data.msg,
                                        icon: "success"
                                    });
                                    
                                    this.fetch()
                                }else{

                                    swal({
                                        title: "Lo sentimos!",
                                        text: res.data.msg,
                                        icon: "error"
                                    });

                                }

                            })

                        }
                    })
                    


                },
                toggleList(){

                    if($("#export-list").hasClass("show")){
                        $("#export-list").removeClass("show")
                    }else{
                        $("#export-list").addClass("show")
                    }

                }
            },
            created(){

                this.fetch()

            }

        })
    </script>


@endpush
