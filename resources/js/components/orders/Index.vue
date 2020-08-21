<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Customer Orders</h4>
      <div class="card-header-actions mr-1">
        <!-- <a class="btn btn-success" href="/agents/topup/create"> </a> -->
      </div>
    </div>
    <div class="card-body px-0">
      <div class="row justify-content-between">
        <div class="col-8 col-md-5">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" @click="filter">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <input type="text" class="form-control" placeholder="Seach" v-model.trim="filters.search" @keyup.enter="filter">
          </div>
        </div>
        
        <div class="col-7">
          <multiselect
          style="width:100px;"
            v-model="filters.pagination.per_page"
            :options="[25,50,100,200]"
            :searchable="false"
            :show-labels="false"
            :allow-empty="false"
            @select="changeSize"
            placeholder="Search">
          </multiselect>
        </div>
        <div class="col-8 col-md-5">
           <label>   FROM</label>
            <input type="date" class="form-control" placeholder="From" v-model="filters.from_date" @change="filter">
        </div>

        <div class="col-8 col-md-5">
           <label>   TO</label>
            <input type="date" class="form-control" placeholder="To" v-model="filters.to_date" @change="filter">
        </div>

      </div>
      <table class="table table-hover">
        <thead>
          <tr>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('id')">ID</a>

              <i class="mr-1 fas" 
              :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th>
              <a href="#" class="text-dark" @click.prevent="sort('mobile')">source</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'source' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'source' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('user_name')">User Name</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'user_name' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'user_name' && filters.orderBy.direction == 'desc'}"></i>
            </th>
             <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('address')">Address</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'address' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'address' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('net_cost')">Net Cost</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'net_cost' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'net_cost' && filters.orderBy.direction == 'desc'}"></i>
            </th>
             <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('grand_total')">Grand Total</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'grand_total' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'grand_total' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('status')">Status</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'status' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'status' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id">
             <!-- @click="editorder(order.id)" -->
            <td class="d-none d-sm-table-cell">{{order.id}}</td>
            <td>
               {{order.source}}
            </td>
            <td class="d-none d-sm-table-cell">
             {{order.user_name}}
                 </td>
                 
            <td class="d-none d-sm-table-cell">
             {{order.address}}
            </td>
            <td class="d-none d-sm-table-cell">
             {{order.net_cost}}
            </td>
            <td class="d-none d-sm-table-cell">
             {{order.grand_total}}
            </td>
            <td class="d-none d-sm-table-cell">
              <div v-if="!isAdmin">
             {{order.status}}
             </div>
             <div v-if="isAdmin">
               <select class="form-control" v-model="order.status" @change="changeStatus(order)">
                 <option value="Pending">Pending</option>
                   <option value="Processing">Processing</option>
                 <option value="Post Extraction in Process">Post Extraction in Process</option>
                   <option value="Post Extracted">Post Extracted</option>
                   <option value="Shipped">Shipped</option>
               </select>

             </div>
            </td>
           
            <td class="d-none d-sm-table-cell" >
              <a :href="`/customer-order/${order.id}/edit`" class="text-muted">Edit</a>
              <div v-if="isAdmin || order.download_pdf==1">

              <a :href="`/customer/download?link=${order.pdf_link}`" class="text-muted">DOWNLOAD PDF</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="row" v-if='!loading && filters.pagination.total > 0'>
        <div class="col pt-2">
          {{filters.pagination.from}}-{{filters.pagination.to}} of {{filters.pagination.total}}
        </div>
        <div class="col" v-if="filters.pagination.last_page>1">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
              <li class="page-item" :class="{'disabled': filters.pagination.current_page <= 1}">
                <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page -  1)"><i class="fas fa-angle-left"></i></a>
              </li>
              <li class="page-item" v-for="page in filters.pagination.last_page" :class="{'active': filters.pagination.current_page == page}">
                <span class="page-link" v-if="filters.pagination.current_page == page">{{page}}</span>
                <a class="page-link" href="#" v-else @click.prevent="changePage(page)">{{page}}</a>
              </li>
              <li class="page-item" :class="{'disabled': filters.pagination.current_page >= filters.pagination.last_page}">
                <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page +  1)"><i class="fas fa-angle-right"></i></a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <content-placeholders v-if="loading">
        <content-placeholders-text/>
      </content-placeholders>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      orders: [],
      isAdmin:false,
      filters: {
        pagination: {
          from: 0,
          to: 0,
          total: 0,
          per_page: 25,
          current_page: 1,
          last_page: 0,
        },
        orderBy: {
          column: 'id',
          direction: 'asc'
        },
        search: '',
        from_date:"",
        to_date:""
      },
      loading: true
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableOrders")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableOrders"))
    } else {
      localStorage.setItem("filtersTableOrders", this.filters);
    }
    this.getRole();
    this.getOrders()
  },
  methods: {
    changeStatus(order){
      axios.post("/api/users/orders/udpate-status",{orderId:order.id,status:order.status});
    },
    getOrders () {
      this.loading = true
      this.orders = []

      localStorage.setItem("filtersTableOrders", JSON.stringify(this.filters));

      axios.post(`/api/users/orders/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.orders = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    getRole(){
       let _this=this;
    axios.get("/check-login-status")
    .then(response=>{
      if(response.status==200){
        _this.isAdmin=response.data.isAdmin;
        console.log(_this.isAdmin);

      }
    });
    },
    editTopup (userId) {
      location.href = `/orders/${userId}/edit`
    },
    // filters
    filter() {
      console.log(this.filters)
      this.filters.pagination.current_page = 1
      this.getOrders()
    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getOrders()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getOrders()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getOrders()
    }
  }
}
</script>
