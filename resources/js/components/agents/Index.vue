<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Send Money</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-success" href="/agents/topup/create">+ Send Money </a>
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
              <a href="#" class="text-dark" @click.prevent="sort('mobile')">Mobile#</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'mobile' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'mobile' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell">
              <a href="#" class="text-dark" @click.prevent="sort('created_at')">Amount</a>
              <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
            </th>
            <th class="d-none d-sm-table-cell"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="topup in topups">
             <!-- @click="editTopup(topup.id)" -->
            <td class="d-none d-sm-table-cell">{{topup.id}}</td>
            <td>
               {{topup.mobile}}
            </td>
            <td class="d-none d-sm-table-cell">
             {{topup.amount}}
                 </td>
           
            <td class="d-none d-sm-table-cell">
              <!-- <a href="#" class="text-muted"><i class="fas fa-pencil-alt"></i></a> -->
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
      <div class="no-items-found text-center mt-5" v-if="!loading && !topups.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>Could not find any items</strong></p>
        <p class="text-muted">Try changing the filters or add a new one</p>
        <a class="btn btn-success" href="/agents/topup/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; Send Money 
        </a>
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
      topups: [],
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
    if (localStorage.getItem("filtersTableTopups")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableTopups"))
    } else {
      localStorage.setItem("filtersTableTopups", this.filters);
    }
    this.getAgentsTopup()
  },
  methods: {
    getAgentsTopup () {
      this.loading = true
      this.topups = []

      localStorage.setItem("filtersTableTopups", JSON.stringify(this.filters));

      axios.post(`/api/agents/topup/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.topups = response.data.data
        delete response.data.data
        this.filters.pagination = response.data
        this.loading = false
      })
    },
    editTopup (userId) {
      location.href = `/agents/topup/${userId}/edit`
    },
    // filters
    filter() {
      console.log(this.filters)
      this.filters.pagination.current_page = 1
      this.getAgentsTopup()
    },
    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getAgentsTopup()
    },
    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getAgentsTopup()
    },
    changePage (page) {
      this.filters.pagination.current_page = page
      this.getAgentsTopup()
    }
  }
}
</script>
