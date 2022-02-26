@extends("layouts.main")

@section("content")

    <div class="d-flex flex-column-fluid" id="shippings-dev">
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
                        <h3 class="card-label">Guías
                    </div>
                    <div class="card-toolbar">

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
                                <input type="text" class="form-control" v-model="query" @keyup="search()" placeholder="Guía #, Warehouse #">
                            </div>
                        </div>
                    </div>

                    <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded table-responsive" id="kt_datatable" style="">
                        <table class="table">
                            <thead>
                                <tr >
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Guía #</span>
                                    </th>
                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 250px;">Fecha</span>
                                    </th>


                                    <th class="datatable-cell datatable-cell-sort">
                                        <span style="width: 130px;">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="guide in guides">

                                    <td class="datatable-cell">
                                        @{{ guide.guide }}
                                    </td>
                                    <td class="datatable-cell">
                                        @{{ dateFormatter(guide.created_at) }} 
                                    </td>

                                    <td>
                                        
                                        <button v-if="guide.dua == null" title="Crear DUA" @click="setInfo(guide)" class="btn btn-success" data-toggle="modal" data-target="#duaCreate"><i class="fas fa-edit"></i></button>
            
                                        <a v-if="guide.dua" title="Ver DUA" class="btn btn-info" :href="'{{ url('/dua/search') }}'+'?dua='+guide.dua.dua"><i class="fas fa-eye"></i></button>
                                        <a v-if="guide.dua" title="Ver DUA" target="_blank" class="btn btn-info" :href="'{{ url('/dua/pdf') }}'+'?dua='+guide.dua.dua"><i class="fas fa-download"></i></button>
                                        
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
                    <!--end: Datatable-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->

        <!-- Modal-->
        <div class="modal fade" id="duaCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Crear DUA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hawb">HAWB</label>
                                    <input type="text" class="form-control" id="hawb" v-model="hawb">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('hawb')">@{{ errors['hawb'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="esser">ESSER</label>
                                    <input type="text" class="form-control" id="esser" v-model="esser">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('esser')">@{{ errors['esser'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client">Cliente</label>
                                    <input type="text" class="form-control" id="client" v-model="client">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('client')">@{{ errors['client'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="awb">AWB</label>
                                    <input type="text" class="form-control" id="awb" v-model="awb">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('awb')">@{{ errors['awb'][0] }}</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="manifest">Manifiesto</label>
                                    <input type="text" class="form-control" id="manifest" v-model="manifest">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('manifest')">@{{ errors['manifest'][0] }}</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="volante">Volante</label>
                                    <input type="text" class="form-control" id="volante" v-model="volante">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('volante')">@{{ errors['volante'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight">Peso (KG)</label>
                                    <input type="text" class="form-control" id="weight" v-model="weight.toFixed(2)">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('weight')">@{{ errors['weight'][0] }}</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pieces">Bultos</label>
                                    <input type="text" class="form-control" id="pieces" v-model="pieces">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('pieces')">@{{ errors['pieces'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dua">DUA</label>
                                    <input type="text" class="form-control" id="dua" v-model="dua">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('dua')">@{{ errors['dua'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tc">TC</label>
                                    <input type="text" class="form-control" id="tc" v-model="tc">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('tc')">@{{ errors['tc'][0] }}</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="arrivalDate">Fecha llegada</label>
                                    <input type="date" class="form-control" id="arrivalDate" v-model="arrivalDate">
                                    <small class="text-danger" v-if="errors.hasOwnProperty('arrivalDate')">@{{ errors['arrivalDate'][0] }}</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                Tracking
                                            </th>
                                            <th>
                                                Warehouse
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="track in contents">
                                            <td>@{{ track.shipping.tracking }}</td>
                                            <td>@{{ track.shipping.warehouse_number }}</td>
                                        </tr>
                                    </tbody>

                                </table>

                            </div>

                        </div>

                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="store()">Crear DUA</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push("scripts")

    @include("dua.create.script")


@endpush