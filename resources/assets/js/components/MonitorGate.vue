<template>
  <div>
        <div class="form-row mb-2 mt-3">
                     
                <div class="col-2">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Gate Rfid</label>
                        <select name="age" class="form-control disabled" v-model="selected">
                            <option v-for="(gate,i) in gates" :key="i" selected :value="gate.id">{{ gate.title }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Search</label>
                        <input type="text" class="form-control"  v-model="searchString" placeholder="Search Driver Name" />
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label class="text-muted text-uppercase" >Filter by date</label>
                        <input class="form-control" type="date" v-model="date">
                    </div>
                </div>

                <div class="col-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="row">
                            <div class="col text-right" :class="{'pr-0 ':!selectedComponent}">
                                <button class="btn btn-block" :class="{'btn-primary' : date, 'btn-danger not-allowed' : !date }" :disabled="!date" @click="selectedComponent = false">Generate</button>
                            </div>
                            <div class="col-3 text-right" v-if="!selectedComponent">
                                <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" :href="'/driver_rfid/public/exportGate/' + selected + '/' + date">Export to Excell</a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div> <!-- end row -->

        <div class="form-row mb-3 mt-1" v-if="!selectedComponent">
                <div class="col text-center p-3 bg-light">
                    <a class="text-dark h5 text-uppercase" style="font-weight: 100" @click="backToLatest()" href="javascript:void(0);">
                        <i class="fa fa-arrow-left"></i> Back to latest
                    </a>
                </div>
            </div>

            <app-monitor-gate-child v-for="(gate,i) in gates" :key="i" 
                                    :search-string="searchString" 
                                    :gate_id="selected" 
                                    v-if="gate.id == selected && selectedComponent">
            </app-monitor-gate-child>

            <app-monitor-gate-search v-for="(gate,i) in gates" :key="i" 
                                    :search-string="searchString" 
                                    :gate_id="selected"
                                    :date="searchDate(date)" 
                                    v-if="gate.id == selected && !selectedComponent">
            </app-monitor-gate-search>

    </div><!-- end template -->

</template>
<script>

    import MonitorGateSearch from './MonitorGateSearch.vue';
    import MonitorGateChild from './MonitorGateChild.vue'; 
    import moment from 'moment';

    export default {

        components: {
            appMonitorGateChild : MonitorGateChild,
            appMonitorGateSearch : MonitorGateSearch
        },

        data() {
            return {
                selected: 1,
                selectedComponent: true,
                searchString: '',
                gates: [],
                date: '',
            }
        },

        created() {
            this.getGates();
        },

        methods: {
            getGates() {
                axios.get('/driver_rfid/public/gates')
                .then(response => {
                    this.gates = response.data
                });
            },

            backToLatest(){
                this.selectedComponent = true;
                this.date = null;
            },

            searchDate(date) {
                return moment(date).format('YYYY-MM-DD');
            },

        }


    }

</script>
<style scoped>
    button {
        cursor: pointer;
    }
    .not-allowed {cursor: not-allowed;}
</style>
