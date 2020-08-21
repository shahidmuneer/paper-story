<template>

       <div >
          <h2 class="text-center" style="padding-top:20px;">Place Order</h2 >
       <div class="row">
		<div  class="col col-12 card " ref="registrationContainer" style="padding:20px;">
      
         <h2 class="text-center" style="color:red;">Shipping Address</h2 >
      <div class="col col-12">
        <div class="col col-12">
        <label>Full Name</label>
        <input type="text" class="form-control" v-model="user.address.full_name" placeholder="FULL NAME TO WHOM YOU WANT TO DELIVER">
        </div>
     </div>
     <div class="col col-12">
        <div class="col col-12">
        <label>Mobile Number</label>
        <input type="text" class="form-control" v-model="user.address.mobile_number" placeholder="MOBILE NUMBER WHO RECEIVE THIS DELIVERY">
        </div>
     </div>
     <div class="col col-12">
        <div class="col col-12">
        <label>Delivery Address</label>
        <textarea type="text" class="form-control"
         v-model="user.address.address" placeholder="ENTER DELIVERY ADDRESS"></textarea>
        </div>
     </div>

     <div class="col col-12 row">
        <div class="col col-6">
        <label>City</label>
        <input type="text" class="form-control" v-model="user.address.city" placeholder="City">
        </div>
         <div class="col col-6">
        <label>State</label>
        <input type="text" class="form-control" v-model="user.address.state" placeholder="State">
        </div>
     </div>
      <div class="col col-12">
        <div class="col col-12">
        <label>Landmark</label>
        <input type="text" class="form-control" v-model="user.address.landmark" placeholder="Landmark">
        </div>
     </div>
 </div>
    <div  class="col col-12 card" v-if="agent_type=='loggedout' " style="padding:20px;">
       <div class="col col-12">
        <div class="col col-12">
        <label>Full Name</label>
        <input type="text" class="form-control" v-model="user.full_name" placeholder="Your FULL NAME">
        </div>
     </div>
     <div class="col col-12">
        <div class="col col-12">
        <label>Mobile Number</label>
        <input type="text" class="form-control" v-model="user.mobile_number" placeholder="Your MOBILE NUMBER">
        </div>
     </div>
     <div class="col col-12 row">
       
        <div class="col col-6">
          <label>OTP</label>
        <input type="number" max='6' :disabled="isOtpVerified" min="6" class="form-control" v-model="otp" v-on:keyup="verifyOtp" placeholder="You will receive message in few minutes">
       
        </div>
        <div class="col col-2">
          <label>&ensp;</label>
          <button class="btn btn-warning form-control" v-if="!optVerified" @click="sendOtp">SEND OTP</button>
        </div>
     </div>
     
     <div class="col col-12">
        <div class="col col-12">
        <label>Email ID</label>
        <input type="email" class="form-control" v-model="user.email" placeholder="Your Email ID">
        </div>
     </div>
     
     <div class="col col-12">
        <div class="col col-12">
        <label>Whatsapp#</label>
        <input type="text" class="form-control" v-model="user.whatsapp_number" placeholder="Your Whatsapp Number">
        </div>
     </div>
      <div class="col col-12 text-center card" style="padding:30px;margin-top:10px;">
        <h2>Price will be provided in order page</h2>
        <h2>(Format will be Provided Later)</h2>
      </div>

    </div>
    <div class="col col-12">
      <!-- <input type="submit" value="PROCEED TO PAY" class="btn btn-primary form-control"> -->

      <button @click="payButton" v-show="agent_type!='edit'" class="btn btn-primary form-control">PROCEED TO PAY</button>
      <button @click="updateOrder" v-show="agent_type=='edit'" class="btn btn-primary form-control">UPDATE ORDER</button>
    </div>
       </div>
      </div>
</template>
    
<style scope>
.social label{
	cursor:pointer;
}
</style>
<script>
// document.getElementById('rzp-button1').onclick = function(e){
   
// }
</script>
<script>
import VueToast from 'vue-toast-notification';
  import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);


// Import one of available themes
import 'vue-toast-notification/dist/theme-default.css';
//import 'vue-toast-notification/dist/theme-sugar.css';
 
Vue.use(VueToast);
export default {
  
  props: ["user","firstPersonFile","secondPersonFile","placeOrder","payButton","agent_type","updateOrder","isOtpVerified"],
  data () {
    return {
      otp:"",
    data:{

      orientation:"POTRIATE",
    size:"A4",
    first_person:{
      name:"",
      image:""
    },
    second_person:{
      name:"",
      image:""
    },
    paper_type:""
    }
    };
  },
  mounted () {
  
  },
  methods:{
    sendOtp(){
//       let instance = Vue.$toast.open('You did it!');
// //Vue.$toast.open({/* options */});
 
// // Force close specific toast
// instance.close();
// // Close all opened toast immediately
// Vue.$toast.clear();
      let _this=this;
       axios.post(`send-otp`,{mobile:this.user.mobile_number})
      .then(response => {
        if(response.data.type=='success'){
          Vue.$toast.open('Otp Sent Successfully !');
        }else{
          Vue.$toast.open(response.message);
        }
      })
    },
    verifyOtp(event){
if(event.target.value.length==4){
       let _this=this;
       axios.post(`verify-otp`,{mobile:this.user.mobile_number,otp:event.target.value})
      .then(response => {
        if(response.data.type=='success'){
          _this.isOtpVerified=true;
          Vue.$toast.open('Otp Verified !');
        }else{
          Vue.$toast.open('OTP invalid send again !');
        }
      })
      }
    },
    
  }
}
</script>
