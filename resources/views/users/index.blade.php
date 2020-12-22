@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="user-dev">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Header-->
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Usuarios
                    </div>
                    <div class="card-toolbar">
                        
                        <!--begin::Button-->
                        <button href="#" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#userModal" @click="create()">
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
                        </span>Nuevo Usuario</button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->
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
                                        <span style="width: 250px;">Rol</span>
                                    </th>

                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 130px;">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users"> 
                                    <td class="datatable-cell">
                                        @{{ user.name }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ user.email }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ user.role.name }}
                                    </td>
                                    <td>
                                        <button v-if="user.role_id < 4" class="btn btn-info" data-toggle="modal" data-target="#userModal" @click="edit(user)" ><i class="far fa-edit"></i></button>
                                        <button v-if="user.role_id < 4" class="btn btn-secondary" @click="erase(user.id)"><i class="far fa-trash-alt"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        <!-- Modal-->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control" id="name" v-model="name">
                            <small v-if="errors.hasOwnProperty('name')">@{{ errors['name'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" v-model="email">
                            <small v-if="errors.hasOwnProperty('email')">@{{ errors['email'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="name">Rol</label>
                            <select class="form-control" V-model="role">
                                <option :value="role.id" v-for="role in roles" v-if="role.id != 4">@{{ role.name }}</option>
                            </select>
                            <small v-if="errors.hasOwnProperty('roleId')">@{{ errors['roleId'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="text" class="form-control" id="password" v-model="password">
                            <small v-if="errors.hasOwnProperty('password')">@{{ errors['password'][0] }}</small>
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmation">Repetir Contraseña</label>
                            <input type="text" class="form-control" id="passwordConfirmation" v-model="passwordConfirmation">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary font-weight-bold" v-if="action == 'create'" @click="store()">Crear</button>
                        <button type="button" class="btn btn-primary font-weight-bold" v-if="action == 'edit'" @click="update()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#user-dev',
            data() {
                return {
                    userId:"",
                    modalTitle:"Nuevo usuario",
                    action:"create",
                    users:[],
                    errors:[],
                    name:"",
                    email: "",
                    password:"",
                    passwordConfirmation:"",
                    pages:0,
                    page:1,
                    role:"",
                    roles:JSON.parse('{!! $roles !!}'),
                    loading:false
                }
            },
            methods: {

                create(){
                    this.modalTitle = "Nuevo usuario"
                    this.action = "create"
                    this.userId = ""
                    this.name = ""
                    this.email = ""
                    this.password = ""
                    this.passwordConfirmation = ""
                    
                },
                store(){

                    this.loading = true
                    axios.post("{{ url('users/store') }}", {name: this.name, email: this.email, password: this.password, password_confirmation: this.passwordConfirmation, roleId: this.role})
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
                            this.password = ""
                            this.passwordConfirmation = ""
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
                    axios.post("{{ url('users/update') }}", {name: this.name, email: this.email, password: this.password, password_confirmation: this.passwordConfirmation, id: this.userId, roleId:this.role})
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
                            this.password = ""
                            this.passwordConfirmation = ""
                            this.userId = ""
                            this.role=""
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
                edit(user){
                    this.modalTitle = "Editar usuario"
                    this.action = "edit"
                    this.name = user.name
                    this.userId = user.id
                    this.email = user.email
                    this.role = user.role_id
                },
                fetch(page = 1){

                    this.page = page

                    axios.get("{{ url('users/fetch') }}"+"/"+page)
                    .then(res => {

                        this.users = res.data.users
                        this.pages = Math.ceil(res.data.usersCount / res.data.dataAmount)

                    })

                },
                erase(id){

                    swal({
                        title: "¿Estás seguro?",
                        text: "Eliminarás este usuario!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            
                            axios.post("{{ url('users/erase') }}", {id: id})
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


                }
            },
            created(){

                this.fetch()

            }

        })
    </script>


@endpush
