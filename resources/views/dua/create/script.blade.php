<script>
    const devArea = new Vue({
        el: '#shippings-dev',
        data() {
            return {
                guides:[],
                query:"",
                errors:[],
                pages:0,
                page:1,
                startDateExport:"",
                endDateExport:"",
                exportType:"",
                loading:false,
                searchPage:1,
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
                contents:[]


            }
        },
        methods: {

            searchPageAction(){

                this.fetch(parseInt(this.searchPage))

            },
            fetch(page = 1){
                
                this.page = page
                
                if(this.query == ""){
                    
                    axios.get("{{ url('/shipping-guide/fetch/') }}"+"/"+page).then(res => {
                    
                        this.guides = res.data.shippingGuides
                        this.pages = Math.ceil(res.data.shippingGuidesCount / res.data.dataAmount)
    
                    })
                }else{

                    this.search()

                }

                

            },
            
            dateFormatter(date){
                
                let year = date.substring(0, 4)
                let month = date.substring(5, 7)
                let day = date.substring(8, 10)
                return day+"-"+month+"-"+year
            },

            search(){
                
                
                if(this.query == ""){
                    
                    this.fetch()

                }else{
                    
                    axios.post("{{ url('/shipping-guide/search') }}", {search: this.query, page: this.page}).then(res =>{

                        this.guides = res.data.shippingGuides
                        this.pages = Math.ceil(res.data.shippingGuidesCount / res.data.dataAmount)
                    })

                }

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
                this.shippingGuideId = guide.id
                this.weight = 0
                let hawb = String(guide.guide).padStart(10, "0")
                this.hawb = "LYC"+hawb
                this.contents = guide.shipping_guide_shipping
                guide.shipping_guide_shipping.map(data => {

                    this.weight += data.shipping.weight

                })


            },
            async store(){
                this.errors = []
                try{

                    const response = await axios.post("{{ url('/dua/store') }}", {
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
                        weight: this.weight,
                        shipping_guide_id: this.shippingGuideId
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