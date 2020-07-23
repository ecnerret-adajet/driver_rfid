<template>


  <div>
               <div clas="row">

                <div id="custom-search-input">
                    <div class="input-group col-sm-12 col-md-12 col-lg-12 mb-2 p-0">

                        <input type="text" class="  search-query form-control"  v-model="searchUser" placeholder="Search" />
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
                                <li v-for="user in filteredUser" class="list-group-item">
                                    <div class="row">   
                                        <div class="col-sm-1">
                                        
                                            <span class="fa-stack fa-lg">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa fa-user-o fa-stack-1x fa-inverse"></i>
                                            </span>

                                        </div>
                                        <div class="col-sm-5">
                                            {{ user.name }}
                                            <br/>
                                            {{ user.email }}
                                            <br/>
                                            <span class="badge badge-primary" v-for="role in user.roles">
                                                 {{role.name}}
                                            </span>
                                        </div>
                                        <div class="col-sm-4">
                                            <span class="text-muted">LAST LOGIN:</span><br/>
                                            {{ moment(user.last_login_at) }}
                                            <br/>
                                            <span class="text-muted">IP:</span>
                                            {{  user.last_login_ip }}
                                        </div>
                                        <div class="col-sm-2 pull-right right">

                                            <div class="btn-group pull-right" role="group" aria-label="Basic example">
                                                <a :href="users_link + user.id + '/edit'" class="btn btn-secondary btn-sm">Edit User</a>
                                               
                                                <a :href="'/driver_rfid/public/pfmc-customer/'+user.id" target="_blank" class="btn btn-success btn-sm ml-1">
                                                    Generate PFMC 
                                                    <i v-if="user.pfmc_customer_code" class="fa fa-check"></i>
                                                    <i v-else class="fa fa-times"></i>
                                                </a>
                                                <a :href="'/driver_rfid/public/lfug-customer/'+user.id" target="_blank" class="btn btn-success btn-sm ml-1">
                                                    Generate LFUG
                                                    <i v-if="user.lfug_customer_code" class="fa fa-check"></i>
                                                    <i v-else class="fa fa-times"></i>
                                                </a>  
                                            </div>
                                                                                        
                                        </div>
                                    </div>
                                </li>
                                <li v-if="filteredUser.length == 0"  class="list-group-item">
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
import moment from 'moment';
export default {
    data() {
        return {
            searchUser: '',
            loading: false,
            users: [],
            users_link: '/driver_rfid/public/users/',
        }
    },

    created() {
        this.getUsers()
    },

    methods: {
        getUsers() {
            this.loading = true
            axios.get('/driver_rfid/public/usersJson')
            .then(response => {
                this.users = response.data
                this.loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM D, Y h:m:s A');
        }
    },

    computed: {
        filteredUser() {
            var user_array = this.users;
            var searchUser = this.searchUser;

            if(!searchUser) {
                return user_array;
            }

            searchUser = searchUser.trim().toLowerCase();

            user_array = user_array.filter(function(item) {
                if(item.name.toLowerCase().indexOf(searchUser) !== -1) {
                    return item;
                }
            })

            return user_array;

        }
    }


}
</script>