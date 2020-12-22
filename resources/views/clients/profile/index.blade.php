@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="profile-dev">

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
                        <h3 class="card-label">Pefil
                    </div>
                    
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nombres</label>
                                <input type="text" class="form-control" id="name" v-model="name">
                                <small style="color: red;" v-if="errors.hasOwnProperty('name')">@{{ errors['name'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="lastname">Apellidos</label>
                                <input type="text" class="form-control" id="lastname" v-model="lastname">
                                <small style="color: red;" v-if="errors.hasOwnProperty('lastname')">@{{ errors['lastname'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="lastname">Email</label>
                                <input type="text" class="form-control" id="email" v-model="email" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="dni">DNI</label>
                                <input type="text" class="form-control" id="dni" v-model="dni">
                                <small style="color: red;" v-if="errors.hasOwnProperty('dni')">@{{ errors['dni'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" id="address" v-model="address">
                                <small style="color: red;" v-if="errors.hasOwnProperty('address')">@{{ errors['address'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" v-model="password">
                                <small style="color: red;" v-if="errors.hasOwnProperty('password')">@{{ errors['password'][0] }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="passwordConfirmation">Repetir Contraseña</label>
                                <input type="password" class="form-control" id="passwordConfirmation" v-model="passwordConfirmation">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="imagePreview">Copia DNI</label>
                                <input type="file" class="form-control" @change="onImageChange">
                                <img :src="imagePreview" style="width: 60%" />
                            </div>
                        </div>

                        <div class="col-12">
                            <p class="text-center">
                                <button class="btn btn-primary" @click="update()">Actualizar</button>
                            </p>
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
            el: '#profile-dev',
            data() {
                return {
                    
                    lastname:"{{ \Auth::user()->lastname }}",
                    dni:"{{ \Auth::user()->dni }}",
                    address:"{{ \Auth::user()->address }}",
                    email:"{{ \Auth::user()->email }}",
                    image:"",
                    name:"{{ \Auth::user()->name }}",
                    imagePreview:"{{ \Auth::user()->dni_picture }}",
                    password:"",
                    passwordConfirmation:"",
                    errors:[],
                    loading:false
                }
            },
            methods: {

                update(){

                    this.loading = true
                    this.errors = []
                    axios.post("{{ url('profile/update') }}", {name: this.name, lastname: this.lastname, dni: this.dni, address: this.address, image: this.image, password: this.password, password_confirmation: this.passwordConfirmation})
                    .then(res => {
                        
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Perfecto!",
                                text: res.data.msg,
                                icon: "success"
                            });
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
                onImageChange(e){
                    this.image = e.target.files[0];

                    this.imagePreview = URL.createObjectURL(this.image);
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                
                    this.createImage(files[0]);
                },
                createImage(file) {
                    let reader = new FileReader();
                    let vm = this;
                    reader.onload = (e) => {
                        vm.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                

            }

        })
    </script>


@endpush