@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="qroptions-dev">

    <div class="container">
            <!--begin::Card-->
            <div class="card card-custom">
                <!--begin::Body-->
                <div class="card-body">
                    <!--begin: Datatable-->
                        
                    <div class="row">
                        <div class="col-md-3 offset-md-3" >
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="label" v-model="label">
                                <label class="form-check-label" for="label">Etiqueta</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="bill" v-model="bill">
                                <label class="form-check-label" for="bill">Facturas</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="text-center">
                                <a class="btn btn-success" :href="'{{ url('/shippings/qr/') }}'+'/'+id+'/'+label+'/'+bill" target="_blank">Continuar</a>
                            </p>
                        </div>
                    </div>
                    

                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>

    </div>

@endsection

@push("scripts")

    <script>
        const devArea = new Vue({
            el: '#qroptions-dev',
            data() {
                return {
                    id: "{{ $id }}",
                    label:true,
                    bill:false
                }
            }
        })
    </script>

@endpush