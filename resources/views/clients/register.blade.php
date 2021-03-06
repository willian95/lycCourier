@extends('layouts.login')

@section('content')
    <div class="login_admin " id="register-dev">

        <div class="loader-cover-custom" v-if="loading == true">
            <div class="loader-custom"></div>
        </div>

        <div class="row">
            <div class="login100-more mask col-md-6"
                style="background-image: url('https://images.unsplash.com/photo-1568731053253-f99d388659f0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=334&q=80');">

                <p>L&CCourier</p>

            </div>
            <div class="login100-form validate-form col-md-6">

                <p class="text-center"><img style="width: 230px;" src="{{ url('/img/logo2.png') }}" alt=""></p>
                <h3 class="text-center">Registro</h3>

                <form v-on:submit.prevent="register" style="width: 100%;">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="name">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Nombres</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('name')">@{{ errors['name'][0] }}</small>
                            </div>
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="lastname">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Apellidos</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('lastname')">@{{ errors['lastname'][0] }}</small>
                            </div>
                        </div>

                        <div class="row">
                            {{--<div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="dni">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">DNI</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('dni')">@{{ errors['dni'][0] }}</small>
                            </div>--}}
                            <div class="col-lg-12">

                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="email">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Email</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('email')">@{{ errors['email'][0] }}</small>
                            </div>
                        </div>

                        <div class="row">

                            {{--<div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Departamento</label>
                                    <select class="form-control" @change="fetchProvinces()" v-model="department">
                                        <option v-for="department in departments" :value="department.id">@{{ department.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('department')">@{{ errors['department'][0] }}</small>
                            
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Provincia</label>
                                    <select class="form-control" v-model="province" @change="fetchDistricts()">
                                        <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('province')">@{{ errors['province'][0] }}</small>
                            
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Distrito</label>
                                    <select class="form-control" v-model="district">
                                        <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                    </select>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('district')">@{{ errors['district'][0] }}</small>
                            
                            </div>--}}

                            <div class="col-lg-12">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="address">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Dirección</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('address')">@{{ errors['address'][0] }}</small>
                            
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="password" v-model="password">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Clave</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('password')">@{{ errors['password'][0] }}</small>
                            </div>
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="password" v-model="passwordConfirmation">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Repetir clave</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="resellerEmail">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Email de afiliado</span>
                                </div>
                                <small>Ingresa el email del socio para afiliarte a su cuenta (opcional)</small>
                                
                            </div>
                        </div>
                    

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Registrate
                        </button>
                        <button class="login100-form-btn" type="button" @click="goToRegister()" style="margin-left: 10px; background: #eee !important; color: #000;">
                            Iniciar sesión
                        </button>
                    </div>
                </form>

            </div>


        </div>

    </div>
@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#register-dev',
            data() {
                return {
                    name:"",
                    lastname:"",
                    dni:"",
                    address:"",
                    email: "",
                    password: "",
                    passwordConfirmation:"",
                    errors:"",
                    departments:[],
                    department:"",
                    provinces:[],
                    province:"",
                    districts:[],
                    district:"",
                    resellerEmail:"",
                    loading:false
                }
            },
            methods: {

                goToRegister(){
                    window.location.href="{{ url('/') }}"
                },
                register(){
                    
                    this.loading = true
                    this.errors = []
                    axios.post("{{ url('/register') }}", {name: this.name, lastname: this.lastname, dni: this.dni, address: this.address, email: this.email, password: this.password, password_confirmation: this.passwordConfirmation, department: this.department, province: this.province, district: this.district, resellerEmail: this.resellerEmail}).then(res => {
                        this.loading = false
                        if(res.data.success){

                            swal({
                                title:"¡Excelente!",
                                text:res.data.msg,
                                icon:"success"
                            }).then(() => {

                                this.name = ""
                                this.lastname = ""
                                this.dni = ""
                                this.address = ""
                                this.email = ""
                                this.password = ""
                                this.passwordConfirmation = ""
                                this.resellerEmail = ""

                            })

                        }else{

                            swal({
                                title:"Lo sentimos!",
                                text:res.data.msg,
                                icon:"error"
                            })

                        }
                        

                    })
                    .catch(err => {
                        this.loading = false
                        swal({
                            text:"Hay unos campos que debe revisar",
                            icon:"error"
                        })
                        this.errors = err.response.data.errors
                    })

                },
                fetchDepartments(){

                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments

                    })

                },
                fetchProvinces(){

                    axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                        this.provinces = res.data.provinces

                    })

                },
                fetchDistricts(){

                    axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                        this.districts = res.data.districts

                    })

                }

            },
            created(){
                this.fetchDepartments()
            }

        })
    </script>


@endpush