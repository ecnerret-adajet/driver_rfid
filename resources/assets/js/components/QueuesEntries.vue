<template>
<div>
        <table class="table table-bordered" :class="{'table-striped' : !loading}">
        <thead>
            <tr class="text-uppercase font-weight-light">
            <th scope="col"> <small>  Queue Name  </small> </th>
            <th scope="col"> <small>  Door ID </small> </th>
            <th scope="col"> <small>  Controller ID </small> </th>
            <th scope="col"> <small>  Truckscale Out ID </small> </th>
            <th scope="col"> <small>  Status </small> </th>
            </tr>
        </thead> 
        <tbody>

            <tr v-for="(queue,i) in driverqueues" v-if="!loading" :key="i">

                <td>
                    {{ queue.title }}
                </td>
                <td width="10%">
                    {{ queue.door }}
                </td>
                <td width="10%">
                  {{ queue.controller }}
                </td>
                <td width="10%">
                  {{ queue.ts_out_controller }}
                </td>
                <td width="10%">
                     <button class="btn btn-outline-success btn-sm disabled">
                            ACTIVE
                    </button>
                </td>

            </tr>
            <tr v-if="driverqueues.length == 0 && !loading">
                <td colspan="4">
                    <div class="row">
                        <div class="col text-center pt-3 pb-3">
                            <span class="display-4 text-muted">
                                Nothing Found
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-if="loading">
                    <td colspan="4">
                        <div class="row">
                            <div class="col">
                                <content-placeholders style="border: 0 ! important;" :rounded="true">
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <hr/>
                                    <content-placeholders-heading :img="true" />
                                    <content-placeholders-text :lines="1" />
                                    <!-- <content-placeholders-text :lines="3" /> -->
                                </content-placeholders>
                             </div>
                        </div>
                    </td>
                </tr>
        </tbody>
        </table>

</div>
</template>
<script>
export default {
    
    data() {
        return {
            driverqueues: [],
            loading: false,
        }
    },

    created() {
        this.getQueue()
    },

    methods: {
        getQueue() {
            this.loading = true
            axios.get('/driverqueues')
            .then(response => {
                this.driverqueues = response.data
                this.loading = false
            })
        }

    }

    
}
</script>
