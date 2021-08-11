<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="srolltodiv">
                    <div class="card-header">Customer</div>
                    <form action="" @submit.prevent="addCustomer" >
                        <div type="form-group">
                            <input type="text" placeholder="Name" class="form-control" v-model="customer.name_customer">
                        </div>
                        <div type="form-group">
                            <input type="text" placeholder="Phone" class="form-control" v-model="customer.phone_customer">
                        </div>
                        <div type="form-group">
                            <input type="text" placeholder="Address" class="form-control" v-model="customer.address_customer">
                        </div>
                        <div type="form-group">
                            <input type="text" placeholder="Email" class="form-control" v-model="customer.email_customer">
                        </div>
                        <div type="form-group">
                            <input type="text" placeholder="City" class="form-control" v-model="customer.city_customer">
                        </div>
                        <div type="form-group">
                            <input type="file" ref="fileupload" @change="onFileChange" class="form-control-file" id="image">
                            <div class="mt-2" id="preview"><img v-if="url" :src="url" class="w-50"/></div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>   
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item " v-bind:class="[{disabled: !pagination. prev_page_url}]">
                                <a class="page-link" href="" @click="fetchCustomers(pagination.prev_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item disabled"><a class="page-link" href="#">{{pagination.current_page}}-{{pagination.last_page}}</a></li>
                            <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                                <a class="page-link" href="#" @click="fetchCustomers(pagination.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                </nav>
                <div class="card-body" v-for="showcustomer in customers" v-bind:key="showcustomer.id_customer">
                    <p style="color:red">{{showcustomer.id_customer}}</p>
                    <p><img class="w-50" :src="'public/images/'+showcustomer.image_customer"/></p>
                    <p>{{showcustomer.name_customer}}</p>
                    <p>{{showcustomer.phone_customer}}</p>
                    <p>{{showcustomer.address_customer}}</p>
                    <p>{{showcustomer.email_customer}}</p>
                    <p>{{showcustomer.city_customer}}</p>
                    <button class="btn btn-danger" @click="deleteCustomer(showcustomer.id_customer)">Delete</button>
                    <button class="btn btn-warning" @click="editCustomer(showcustomer)">Edit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
       data(){
           return {
               customers: [],
               url:"",
               customer:{
                   id_customer:'',
                   name_customer:'',
                   phone_customer:'',
                   address_customer:'',
                   email_customer:'',
                   city_customer:'',
                   image_customer:'',
               },
               id_customer:'',
               pagination:{},
               edit:true
           }
       },
       created(){
           this.fetchCustomers();
       },
       methods:{
           onFileChange:function(e){
               const file = e.target.files[0];
               this.url = URL.createObjectURL(file);
           },
           fetchCustomers:function(page_url){
               let vm = this;
               page_url = page_url || 'api/customer';
               fetch(page_url)
               .then(res => res.json())
               .then(res => {
                  this.customers = res.data;
                  vm.makePagination(res.meta, res.links);
               })
               .catch(err => console.log(err))
           },
           makePagination:function(meta, links){
               let pagination = {
                   current_page : meta.current_page,
                   last_page : meta.last_page,
                   next_page_url : links.next,
                   prev_page_url : links.prev
               }
               this.pagination = pagination;
           },
           deleteCustomer:function(id){
               if(confirm('Bạn muốn xóa')){
                   axios.delete(`api/customer/${id}`)
                   .then(res => {
                       alert('Đã xóa');
                       this.fetchCustomers();
                   })
                   .catch(err => console.log(err))
               }
           },
           addCustomer:function(){
               if(this.edit === false){
                   //add
                   let formData = new FormData();

                   formData.append('name_customer',this.customer.name_customer);
                   formData.append('phone_customer',this.customer.phone_customer);
                   formData.append('address_customer',this.customer.address_customer);
                   formData.append('email_customer',this.customer.email_customer);
                   formData.append('city_customer',this.customer.city_customer);
                    if(document.getElementById("image").files[0]){
                        formData.append('image_customer',document.getElementById("image").files[0]);
                    }
                   axios.post(`api/customer`,formData,{
                       header:{
                           'Content-Type' :'multipart/form-data'
                       }
                   })
                   .then(res => {
                       alert('Đã thêm')
                       this.customer.name_customer = "";
                       this.customer.phone_customer = "";
                       this.customer.address_customer = "";
                       this.customer.email_customer = "";
                       this.customer.city_customer = "";
                       this.$refs.fileupload.value = "";
                       this.fetchCustomers();
                   })
                   .catch(err => console.log(err))
               }else{
                   //update
                   let formData = new FormData();

                   formData.append('name_customer',this.customer.name_customer);
                   formData.append('phone_customer',this.customer.phone_customer);
                   formData.append('address_customer',this.customer.address_customer);
                   formData.append('email_customer',this.customer.email_customer);
                   formData.append('city_customer',this.customer.city_customer);
                    if(document.getElementById("image").files[0]){
                        formData.append('image_customer',document.getElementById("image").files[0]);
                    }
                    formData.append("_method","PUT");
                   axios.post(`api/customer/${this.customer.id_customer}`,formData,{
                       header:{
                           'Content-Type' :'multipart/form-data'
                       }
                   })
                   .then(res => {
                       alert('Sửa thành công')
                       this.customer.name_customer = "";
                       this.customer.phone_customer = "";
                       this.customer.address_customer = "";
                       this.customer.email_customer = "";
                       this.customer.city_customer = "";
                       this.url = "";
                       this.fetchCustomers();
                   })
                   .catch(err => console.log(err))
               }
           },
           editCustomer:function(showcustomer){
               const el = document.querySelector("#srolltodiv");
               let rect = el.getBoundingClientRect();
               window.scrollTo(rect.left, rect.top)

                this.edit == true;
                this.customer.id_customer = showcustomer.id_customer;
                this.customer.name_customer = showcustomer.name_customer;
                this.customer.phone_customer = showcustomer.phone_customer;
                this.customer.address_customer = showcustomer.address_customer;
                this.customer.email_customer = showcustomer.email_customer;
                this.customer.city_customer = showcustomer.city_customer;
                this.url = `public/images/${showcustomer.image_customer}`;
           }
       }
    }
</script>
