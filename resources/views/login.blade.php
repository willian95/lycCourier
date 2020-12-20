@extends('layouts.login')

@section('content')
    <div class="login_admin " id="login-dev">

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
                <h3 class="text-center">Login</h3>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" v-model="email" v-on:keyup.enter="login()">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Email</span>
                </div>


                <div class="wrap-input100 validate-input">
                    <input class="input100" type="password" v-model="password" v-on:keyup.enter="login()">
                    <span class="focus-input100"></span>
                    <span class="label-input100">Password</span>
                </div>




                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" @click="login()">
                        Entrar
                    </button>
                </div>

            </div>


        </div>

    </div>
@endsection

@push("scripts")

    @if(session('alert'))
        <script>
            swal({
             
                text:"{{ session('alert') }}",
                icon:"success"
            })
        </script>
    @endif

    <script>
        const devArea = new Vue({
            el: '#login-dev',
            data() {
                return {
                    email: "",
                    password: "",
                    loading:false
                }
            },
            methods: {

                login(){
                    this.loading = true
                    axios.post("{{ url('/login') }}", {email: this.email, password: this.password}).then(res => {
                        this.loading = false
                        if(res.data.success == true){

                            swal({
                                title: "Excelente!",
                                text: res.data.msg,
                                icon: "success"
                            }).then(() => {
                                //console.log(res.data)
                                window.location.href=res.data.url
                            })
                            

                        }else{
                            this.loading = false
                            swal({
                                title:"Lo sentimos",
                                text:res.data.msg,
                                icon:"error"
                            })

                        }

                    })

                }

            }

        })
    </script>


@endpush