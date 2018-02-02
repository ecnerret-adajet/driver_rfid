<template>

    <div class="card mx-auto mb-3">

         <div class="card-header p-0">

            <div class="row p-3">
                <div class="col-10">
                    <div class="form-group">
                        <label>Filter by date</label>
                        <input class="form-control" type="date" v-model="date">
                    </div>
                </div>
                <div class="col-2">
                    <label>&nbsp;</label>
                    <button class="btn btn-block btn-outline-primary" :disabled="!date" @click="selectedComponent = false">Generate</button>
                </div>
            </div>

            <div class="row  pt-3 pl-3 pr-3" v-if="!selectedComponent">
                <div class="col text-center p-3 bg-secondary">
                    <a class="text-light h5" style="font-weight: 100" @click="backToLatest()" href="javascript:void(0);">
                        Back to latest
                    </a>
                </div>
            </div>

        </div>
        

          
            <app-entries v-if="selectedComponent"></app-entries>

            <app-search-entries 
                v-if="!selectedComponent"
                :date="searchDate(date)"
            ></app-search-entries>


    </div><!-- end card -->

</template>

<script>
import moment from 'moment';
import _ from 'lodash';
import Entries from './Entries.vue';
import SearchEntries from './SearchEntries.vue';

export default {

    data() {
        return {
            selectedComponent: true,         
            date: '',
        }
    },

    components: {
        appEntries : Entries,
        appSearchEntries : SearchEntries,
    },

    methods: {
        backToLatest(){
            this.selectedComponent = true;
            this.date = null;
        },

       searchDate(date) {
           return moment(date).format('YYYY-MM-DD');
       },
    },

}
</script>

