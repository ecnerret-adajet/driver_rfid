<template>
<div>

    <div class="row">
        <div class="input-group search pull-right">
            <span class="input-group-addon opener">
            <i class="material-icons">search</i>
            </span>
            <input type="text" v-model="searchString"  class="form-control" placeholder="Search">
            <span class="input-group-addon">
            <i class="material-icons">more_vert</i>
            </span>
            <span class="input-group-addon opener">
            <i class="material-icons">clear</i>
            </span>
        </div>
    </div>

    <div class="had-container">

        <div v-if="!loading">
            <ul class="collection">
                <li v-for="card in filteredCard" class="collection-item avatar">
                    <i class="material-icons circle">folder</i>
                    <span class="title">{{card.CardNo}}</span>
                    <p>
                     <strong>Assigned Cardholder:</strong> <span v-for="cardholder in card.binder">{{ cardholder.cardholder_id }}</span>
                    </p>

                    <p>
                    </p>

                    <p class="secondary-content right-align">
                        <a :href="cards_link + card.CardID"><i class="material-icons">open_in_new</i></a><br/>
                        <!-- <span>
                        COUNT UPDATE: {{ truck.update_count == null ? 0 : truck.update_count  }}
                        </span> -->
                    </p>
                </li>
                <li v-if="filteredCard.length == 0" class="collection-item avatar center-align">
                    <span class="title">NO TRUCK FOUND</span>
                </li>
            </ul>
        </div>

        <div class="center-align" style="padding-top:50px" v-if="loading">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-green-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
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
            cards_link: '/driver_rfid/public/bind/create/',
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
            axios.get('http://localhost/driver_rfid/public/cardsJson')
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