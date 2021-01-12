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
                        <h3 class="card-label">Perfil
                    </div>
                    
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Nombres</label>
                                <input type="text" class="form-control" id="name" v-model="name" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="lastname">Apellidos</label>
                                <input type="text" class="form-control" id="lastname" v-model="lastname" readonly>
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
                                <input type="text" class="form-control" id="dni" v-model="dni" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="text" class="form-control" id="phone" v-model="phone" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Departamento</label>
                                <select class="form-control" @change="fetchProvinces()" v-model="department" disabled>
                                    <option v-for="department in departments" :value="department.id">@{{ department.name }}</option>
                                </select>
                            </div>
                        
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Provincia</label>
                                <select class="form-control" v-model="province" @change="fetchDistricts()" disabled>
                                    <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                                </select>
                            </div>
                        
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Distrito</label>
                                <select class="form-control" v-model="district" disabled>
                                    <option v-for="district in districts" :value="district.id">@{{ district.name }}</option>
                                </select>
                            </div>
                        
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" id="address" v-model="address" readonly>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="imagePreview">Copia DNI (Parte delantera)</label>
                                <p>
                                <img :src="imagePreview" style="width: 60%" />
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="imagePreview">Copia DNI (Parte trasera)</label>
                                <p>
                                <img :src="imagePreviewBack" style="width: 60%" />
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
            el: '#profile-dev',
            data() {
                return {
                    
                    lastname:"{{ $user->lastname }}",
                    dni:"{{ $user->dni }}",
                    address:"{{ $user->address }}",
                    email:"{{ $user->email }}",
                    image:"",
                    name:"{{ $user->name }}",
                    imagePreview:"{{ $user->dni_picture }}",
                    imagePreviewBack:"{{ $user->dni_picture_back }}",
                    password:"",
                    phone:"{{ $user->phone }}",
                    passwordConfirmation:"",
                    errors:[],
                    departments:[],
                    department:"{{ $user->department_id }}",
                    provinces:[],
                    province:"{{ $user->province_id }}",
                    districts:[],
                    district:"{{ $user->district_id }}",
                    loading:false
                }
            },
            methods: {

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

                },
                createdFetchDeparments(){
                    
                    axios.get("{{ url('/departments') }}").then(res => {

                        this.departments = res.data.departments
                        axios.get("{{ url('/provinces/') }}"+"/"+this.department).then(res => {

                            this.provinces = res.data.provinces
                            axios.get("{{ url('/districts/') }}"+"/"+this.department+"/"+this.province).then(res => {

                                this.districts = res.data.districts

                            })
                        })
                    })

                }
                

            },
            created(){

                this.createdFetchDeparments()

            }

        })
    </script>


@endpush
