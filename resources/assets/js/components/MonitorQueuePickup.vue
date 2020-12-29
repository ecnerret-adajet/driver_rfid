<template>
  <div>

            <!-- <div class="row mt-4">
                <div class="col-10">
                    <div class="form-group">
                        <label class="text-uppercase text-muted">Filter by date</label>
                        <input class="form-control" type="date" v-model="date">
                    </div>
                </div>
                <div class="col-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-block btn-outline-primary" :disabled="!date" @click="generatePickup()">Generate</button>
                </div>
            </div> -->

            <div class="form-row mb-2 mt-4">

                <div class="col-2" v-if="!generate">
                    <div class="form-group">
                        <select name="age" class="form-control" v-model="selected">
                            <option selected value="1">All</option>
                            <option value="2">Served</option>
                            <option value="3">Unserved</option>
                            <option value="4">Still In Plant</option>
                        </select>
                    </div>
                </div>

                <div :class="{'col-10' : !generate, 'col-12' : generate }">
                    <div class="form-group">
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name or Plate number" />
                    </div>
                </div>

            </div> <!-- end row -->

            <div class="row mb-3" v-if="generate">
                <div class="col">
                    <a  @click="backToLatest()" href="javascript:void(0);" class="text-uppercase font-weight-light btn-block">
                        Back to latest
                    </a>
                </div>
            </div>

            <!-- <app-search-pickup-feed :search="searchString" v-if="generate" :date="searchDate(date)"></app-search-pickup-feed> -->
            <app-pickup-feed :search="searchString"  v-if="selected == 1"></app-pickup-feed>
            <app-served :search="searchString"  v-if="selected == 2"></app-served>
            <app-unserved :search="searchString"  v-if="selected == 3"></app-unserved>
            <app-pickup-in-plant :search="searchString"  v-if="selected == 4"></app-pickup-in-plant>


  </div>
</template>
<script>
    import moment from 'moment';
    import PickupFeed from './PickupFeed.vue';
    // import SearchPickupFeed from './SearchPickupFeed';
    import Served from './Served.vue';
    import Unserved from './Unserved.vue';
    import PickupInPlant from './PickupInPlant.vue';

    export default {
        data() {
            return {
                generate: false,
                filter: '',
                searchString: '',
                date: '',
                selected: 1,
            }
        },

        components: {
            // appSearchPickupFeed : SearchPickupFeed,
            appPickupFeed : PickupFeed,
            appServed : Served,
            appUnserved : Unserved,
            appPickupInPlant : PickupInPlant
        },

        methods: {
             backToLatest(){
                this.selected = 1;
                this.generate = false;
                this.date = null;
            },

            generatePickup() {
                 this.generate = true;
                 this.selected = false;
            },

            toggledButton(){
                return this.selected = !this.selected;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            },
        },

    }
</script>
<style scoped>
    button {
        cursor: pointer;
    }
</style>
