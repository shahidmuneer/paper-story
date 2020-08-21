<template>
      <div class="col-12 social">
		  <center>
        <h2 class="text-center" style="padding-top:20px;">SELECT THE SOURCE</h2 >
		<div class="col-lg-12 col-sm-2">
      <center>
<div class="col col-6 offset-3" style="margin-top:10px;">
          <div class="row">
                <div class="col col-2">
                    <input type="radio" class="form-control" v-model="source" value="facebook">
                </div>
                <div class="col col-2">
                  Facebook
                </div>
                  <div class="col col-2">
                  <img src="/icon/facebook.svg" width="50px">
                  </div>
          </div>
</div>
</center>

<center>
<div class="col col-6 offset-3" style="margin-top:10px;">
          <div class="row">
                <div class="col col-2">
                    <input type="radio" class="form-control" v-model="source" value="instagram">
                </div>
                <div class="col col-2">
                  Instagram
                </div>
                  <div class="col col-2">
                  <img src="/icon/instagram.svg" width="50px">
                  </div>
          </div>
</div>
</center>

<center>
<div class="col col-6 offset-3" style="margin-top:10px;">
          <div class="row">
                <div class="col col-2">
                    <input type="radio" class="form-control" v-model="source" value="whatsapp">
                </div>
                <div class="col col-2">
                  whatsapp
                </div>
                  <div class="col col-2">
                  <img src="/icon/whatsapp.svg" width="50px">
                  </div>
          </div>
</div>
</center>
		</div>
		  </center>
      <input type="submit"  class="btn btn-primary " value="next" @click="next()" style="float:right;">
    </div>
</template>
<style scope>
.social label{
	cursor:pointer;
}
</style>
<script>
import Router from 'vue-router';

export default {
  data () {
    return {
	  balance: 0,
	  source:"facebook"
    }
  },
  mounted () {
    this.getRolesCount()
  },
  methods: {
    getRolesCount () {
      axios.get(`/api/users/balance/count`)
      .then(response => {
        this.balance = response.data
      })
    },
    next(){
      location.replace(`/${this.source}`);
    }
  },
  watch:{
       source: function(val, oldVal) {
      //      if(val=="instagram"){
			//    router.push({name:"instagram"});
		  //  }
		  //  if(val=="facebook"){
			//    router.push({name:"facebook"});
		  //  }
		  //  if(val=="whatsapp"){
			//    console.log(this.$route)
		  //  }
        }
    }
}
</script>
