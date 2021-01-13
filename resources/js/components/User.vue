<template>
    <div>
        <div class="row mt-5">
            <div class="col-md-4">
                Date: {{currentDate}}
            </div>
            <div class="col-md-8">
                <h1><u>'My Awesome List'</u></h1>
            </div>
        </div>

        <p><span>{{ addEditHeader }}</span> <span class="float-right">By: awais</span></p>
        <div class="row">
            <input type="hidden" name="id" v-model="id">
            <div class="col">
                <input class="form-control" type="text" name="name" value="" placeholder="Full Name" v-model="name">
                <span class="errorMessage">{{nameError}}</span>
            </div>
            <div class="col">
                <input class="form-control" type="email" name="email" value="" placeholder="Email" v-model="email">
                <span class="errorMessage">{{emailError}}</span>
            </div>
            <div class="col">
                <button class="btn btn-primary" v-on:click="storeItem()">Submit</button>
            </div>
        </div>
        <br/>

        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <td>Name</td>
                    <td>Email</td>
                    <td colspan="1">Actions</td>
                </tr>
                </thead>

                <tbody>
                <tr v-for="(item,index) in items">
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td>
                        <button class="btn btn-danger" v-on:click="deleteItem(item.id,index)">Delete</button>
                        <button class="btn btn-primary" v-on:click="editItem(item.id)">Edit</button>
                    </td>
                </tr>
                <tr v-if="(items.length == 0)">
                    <td colspan="5" class="text-center">
                        No Record Found
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                addEditHeader: 'Add User',
                id: 0,
                name: '',
                nameError: '',
                email: '',
                emailError: '',
                currentDate: '',
                mailformat: "/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/",
                items: [
                    id => '',
                    name => '',
                    email => '',
                ]
            }
        },

        created: function () {
            this.fetchItems();
        },

        methods: {
            fetchItems() {
                let uri = 'users';
                this.axios.get(uri).then((response) => {
                    console.log(response.data);
                    this.items = response.data.users;
                    this.currentDate = response.data.currentDate;
                });
            },
            storeItem() {
                this.nameError = '';
                this.emailError = '';
                if(this.name == '')
                {
                    this.nameError = 'Name field can not be empty.';
                }else if(this.email == '')
                {
                    this.emailError = 'Email field can not be empty.';
                }else if(!this.validateEmail(this.email))
                {
                    this.emailError = 'Invalid email.';
                }
                else
                {
                    this.axios.post('user/store',{id: this.id,name: this.name,email: this.email})
                    .then((response)=>{
                        if(response.data.status == '1')
                        {
                            this.id = 0;
                            this.name = '';
                            this.email = '';
                        }
                        alert(response.data.message);
                        this.addEditHeader = 'Add User';
                        this.fetchItems();
                    })
                }
            },
            editItem(id) {
                let uri = `user/${id}`;
                    this.axios.get(uri).then((response) => {
                        if(response.data.status == '1')
                        {
                            this.addEditHeader = 'Edit User';
                            this.id = response.data.item.id;
                            this.name = response.data.item.name;
                            this.email = response.data.item.email;
                        }
                        else
                        {
                            alert(response.data.message);
                        }
                    });
            }
            ,
            deleteItem(id, index) {
                if (confirm("Do you really want to delete?")) {
                    let uri = `users/delete/${id}`;
                    this.items.splice(id, 1);
                    this.items.splice(index, 1);

                    this.axios.get(uri).then((response) => {
                        alert(response.data);
                        this.fetchItems();
                    });
                }
            },
            validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
            }
        }
    }
</script>
