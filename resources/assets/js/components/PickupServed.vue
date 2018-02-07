<template>
    <div>     

        <div class="row mt-4" v-if="selected">
            <div class="col-10">
                <div class="form-group">
                    <label class="text-uppercase text-muted">Filter by date</label>
                    <input class="form-control" type="date" v-model="date">
                </div>
            </div>
            <div class="col-2">
                <label>&nbsp;</label>
                <button class="btn btn-block btn-outline-primary" :disabled="!date" @click="selected=false">Generate</button>
            </div>
        </div>

        <div class="row mb-3" v-if="!selected">
            <div class="col">
                <a  @click="backToLatest()" href="javascript:void(0);" class="text-uppercase font-weight-light btn-block">
                    Back to latest
                </a>
            </div>
        </div>

         <div class="form-row mt-3 mb-2">

            <div class="col">
                <div class="form-group">
                    <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name" />
                </div>
            </div>

        </div> <!-- end row -->

            <app-pickup-served-current v-if="selected" :searchString="searchString"></app-pickup-served-current>
            <app-pickup-served-search v-if="!selected" :searchString="searchString" :date="searchDate(date)"></app-pickup-served-search>
         
            
    </div>
</template>
<script>
    import moment from 'moment';
    import PickupServedCurrent from './PickupServedCurrent.vue';
    import PickupServedSearch from './PickupServedSearch.vue';

    export default {
        
        components: {
            appPickupServedCurrent : PickupServedCurrent,
            appPickupServedSearch : PickupServedSearch,
        },

        data() {
            return {
                searchString: '',
                date: '',
                selected: true,
            }
        },

        methods: {
            backToLatest() {
                this.selected = true;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            },

        },
    }
</script>