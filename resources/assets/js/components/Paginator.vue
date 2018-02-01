<template>
 <div class="card-footer text-muted">
    <div  class="row">
        <div class="col">
            <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage - 1)"> Previous </button>
                <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
            <button :disabled="!showNextLink()" class="btn btn-default btn-sm" v-on:click="setPage(currentPage + 1)"> Next </button>
        </div>
    </div>
</div>
</template>

<script>
    export default {

         props: ['resource'],

        data() {
            return {
                currentPage: 0,
                itemsPerPage: 5,
                resultCount: 0,
            }
        },

        methods: {
            
            setPage(pageNumber) {
                this.currentPage = pageNumber;         
            },

            resetStartRow() {
                this.currentPage = 0;
            },

            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },

            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            }
        },

        computed: {
            
            filteredEntries() {
                const vm = this;
                
                return _.filter(vm.resource, function (item) {
                    return ~item.driver_name.toLowerCase().indexOf(vm.searchKey.trim().toLowerCase());
                });
            },

            totalPages() {
            return Math.ceil(this.filteredEntries.length / this.itemsPerPage)
            },

            paginateEntries() {

                var index = this.currentPage * this.itemsPerPage;
                var entries_array = this.filteredEntries.slice(index, index + this.itemsPerPage);

                if (this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                } 
                
                return entries_array;
            }
        },
    }
</script>

<style scoped>
    button {
        cursor: pointer;
    }
</style>