<script>
    const devArea = new Vue({
        el: '#shippings-dev',
        data() {
            return {
                duas:[],
                links:[],
                currentPage:"",
                totalPages:"",
                path:"",
                linkClass:"page-link",
                activeLinkClass:"page-link active-link bg-main",
                query:"",
                errors:[],
                startDateExport:"",
                endDateExport:"",
                exportType:"",
                loading:false,
                shippings:[],

                hawb:"",
                esser:"",
                client:"",
                volante:"",
                tc:"",
                arrivalDate:"",
                dua:"",
                manifest:"",
                awb:"",
                pieces:0,
                weight:0,
                shippingGuideId:"",
                contents:[],
                id:""


            }
        },
        methods: {

            searchPageAction(){

                this.fetch(parseInt(this.searchPage))

            },
            fetch(url="{{ url('/dua/fetch/') }}"){
                
                    
                axios.get(url).then(res => {
                    
                    this.duas = res.data.data
                    this.links = res.data.links
                    this.currentPage = res.data.current_page
                    this.totalPages = res.data.last_page
                    this.path = res.data.path

                })
            
            },
            
            dateFormatter(date){
                
                let year = date.substring(0, 4)
                let month = date.substring(5, 7)
                let day = date.substring(8, 10)
                return day+"-"+month+"-"+year
            },
        
            toggleList(){

                if($("#export-list").hasClass("show")){
                    $("#export-list").removeClass("show")
                }else{
                    $("#export-list").addClass("show")
                }

            },

            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            setInfo(guide){
                this.id = guide.id
                this.hawb = guide.hawb
                this.esser = guide.esser
                this.client = guide.client
                this.volante = guide.volante
                this.tc = guide.tc
                this.arrivalDate = guide.real_date
                this.dua = guide.dua
                this.manifest = guide.manifest
                this.awb = guide.awb
                this.pieces = guide.pieces
                this.weight = guide.weight
                this.contents = guide.shipping_guide.shipping_guide_shipping

            },
            async store(){
                this.errors = []
                try{

                    const response = await axios.post("{{ url('/dua/store') }}", {
                        id:this.id,
                        hawb: this.hawb,
                        esser: this.esser,
                        client: this.client,
                        volante: this.volante,
                        tc: this.tc,
                        arrivalDate: this.arrivalDate,
                        dua: this.dua,
                        manifest: this.manifest,
                        awb: this.awb,
                        pieces: this.pieces,
                        weight: this.weight
                    })

                    if(response.data.success == true){

                        swal({
                            title: "Perfecto!",
                            text: response.data.message,
                            icon: "success"
                        }).then(()=> {
                            window.location.reload()
                        });
                    

                    }else{

                        swal({
                            text: response.data.message,
                            icon: "error"
                        });

                    }

                }catch(err){
                  
                    this.loading = false
                    this.errors = err.response.data.errors
                

                }

            }

        },
        created(){

            this.fetch()

        }

    })
</script>