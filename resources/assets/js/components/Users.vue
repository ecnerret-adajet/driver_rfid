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
                                            <span v-for="role in user.roles">
                                                 {{role.name}}
                                            </span>
                                        </div>
                                        <div class="col-sm-3">
                                            {{ moment(user.last_login_at) }}
                                        </div>
                                        <div class="col-sm-3 pull-right right">
                                            <div class="dropdown pull-right">
                                                <a href="javascript:void(0);" data-toggle="dropdown">
                                                   <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="#">
                                                         Deactivate User
                                                        </a>
                                                    </li>
                                                </ul>
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
                         <div class="center-align" style="padding-top: 50px" v-if="loading">
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
        }
    },

    created() {
        this.getUsers()
    },

    methods: {
        getUsers() {
            this.loading = true
            axios.get('http://localhost/driver_rfid/public/usersJson')
            .then(response => {
                this.users = response.data
                this.loading = false
            });
        },

        moment(date) {
            return moment(date).format('MMMM  d, Y h:m:s A');
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