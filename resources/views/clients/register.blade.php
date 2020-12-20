@extends('layouts.login')

@section('content')
    <div class="login_admin " id="register-dev">

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
                            <div class="col-lg-6">
                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="dni">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">DNI</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('dni')">@{{ errors['dni'][0] }}</small>
                            </div>
                            <div class="col-lg-6">

                                <div class="wrap-input100 validate-input">
                                    <input class="input100" type="text" v-model="email">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Email</span>
                                </div>
                                <small style="color: red;" v-if="errors.hasOwnProperty('email')">@{{ errors['email'][0] }}</small>
                            </div>
                        </div>

                        <div class="row">
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
                    

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit">
                            Registrate
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
                    loading:false
                }
            },
            methods: {

                register(){
                    
                    this.loading = true
                    axios.post("{{ url('/register') }}", {name: this.name, lastname: this.lastname, dni: this.dni, address: this.adress, email: this.email, password: this.password, password_confirmation: this.passwordConfirmation}).then(res => {

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

                        swal({
                            text:"Hay unos campos que debe revisar",
                            icon:"error"
                        })

                        this.loading = false
                        this.errors = err.response.data.errors
                    })

                }

            }

        })
    </script>


@endpush