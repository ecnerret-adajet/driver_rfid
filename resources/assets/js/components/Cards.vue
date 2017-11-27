<template>
<div>


    <div clas="row">
        <div id="custom-search-input">
            <div class="input-group col-sm-12 col-md-12 col-lg-12 mb-2 p-0">
                <input type="text" class="  search-query form-control"  v-model="searchString" placeholder="Search" />
                <span class="input-group-btn">
                <button class="btn btn-danger" type="button">
                <i class="fa fa-search"></i>
                </button>
                </span>
            </div>                
        </div>
    </div> <!-- end row -->


         <div class="row">
        <div class="col-sm-12">
            <div v-if="!loading">
                <ul class="list-group">
                    <li v-for="card in filteredCard" class="list-group-item">
                        <div class="row">   
                            <div class="col-sm-1">

                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-user-o fa-stack-1x fa-inverse"></i>
                                </span>
                            
                            </div>
                            <div class="col-sm-8">
                                {{card.CardNo}}
                                <br/>
                                <span v-for="binder in card.binders">
                                    <span v-for="rfid in binder">
                                        {{ rfid.name }}
                                    </span>
                                </span>
                            </div>
                            <div class="col-sm-3 pull-right right">
                                <div class="dropdown pull-right">
                                    <a href="javascript:void(0);" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a :href="cards_link + card.CardID">
                                                Edit Card
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </li>
                    <li v-if="filteredCard.length == 0"  class="list-group-item">
                        <div class="row">
                            <div class="col-sm-12 center">
                                <span>NO RECORD FOUND</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
             <div class="center-align" style="padding-top: 50px; display: flex; align-items: center; justify-content: center;" v-if="loading">
                <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
                    <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
                </svg>	
            </div>
        </div>
    </div>

</div>
</template>

<script>
export default {
    data() {
        return {
            searchString: '',
            cards_link: '/bind/create/',
            cards:[],
            loading: false
        }
    },

    created() {
        this.getCards()
    },

    methods: {
        getCards() {
            this.loading = true
            axios.get('/cardsJson')
            .then(response => {
                this.cards = response.data
                this.loading = false
            });
        }
    },

    computed: {
        filteredCard() {
            var cards_array = this.cards;
            var searchString = searchString;

            if(!searchString) {
                return cards_array;
            }

            searchString = searchString.trim().toLowerCase();

            cards_array = cards_array.filter(function(item){
                if(item.CardNo.toLowerCase().indexOf(searchString) !== -1) {
                    return item;
                }
            })

            return cards_array;
        }
    }



}
</script>