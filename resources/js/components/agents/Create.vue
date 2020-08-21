<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Send Money</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Save</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="form-group">
            <label>Mobile#</label>
            <input type="number" class="form-control" :class="{'is-invalid': errors.mobile}" v-model="topup.mobile" placeholder="+547...">
            <div class="invalid-feedback" v-if="errors.mobile">{{errors.mobile[0]}}</div>
          </div>
          <div class="form-group">
            <label>Amount</label>
            <input type="number" class="form-control" :class="{'is-invalid': errors.amount}" v-model="topup.amount">
            <div class="invalid-feedback" v-if="errors.amount">{{errors.amount[0]}}</div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      topup: {
      },
      errors: {},
      submiting: false
    }
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`/api/agents/topup/store`, this.topup)
        .then(response => {
          this.$toasted.global.error('Topup Successful to number!')
          location.href = '/agents/topup'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
   
  }
}
</script>
