<template>
      <div class="col-12 whatsapp">
          <cover-page v-show="page[0].active" :firstPersonFile="firstPersonFile" :secondPersonFile="secondPersonFile" v-bind:prevData="prevData"></cover-page>
          <color-page  v-show="page[1].active" v-bind:colors='colors'
              v-bind:background='background' :backgroundImage='backgroundImage'
               v-bind:gradient='gradient'  v-bind:prevColorData="prevColorData"></color-page>
          <upload-media  v-show="page[2].active" :generatePdf="generatePdf" ></upload-media>
         
          <order-confirmation v-show="page[3].active" :firstPersonFile="firstPersonFile" v-bind:download_pdf="download_pdf"
           :secondPersonFile="secondPersonFile" v-bind:total_pages="total_pages" v-bind:net_cost="net_cost" v-bind:gst="gst" v-bind:grand_total="grand_total"
            v-bind:prevData="prevData" :placeOrder='placeOrder'></order-confirmation>

          <user-registration 
          :isOtpVerified="isOtpVerified"
          v-show="page[4].active" :firstPersonFile="firstPersonFile" :updateOrder="updateOrder"
          :secondPersonFile="secondPersonFile" v-bind:user="user" :payButton="payButton" :agent_type="agent_type"></user-registration>
         

      <!-- <button @click="payButton">PROCEED TO PAY</button> -->
     
      <div class="col col-12 row" style="margin-top:20px;">
         <div class="col col-6">
          <button class="btn btn-danger" :disabled="this.current_page<1"  @click="previousPage">BACK</button> 
         </div>
         <div class="col col-6">
             <h3 style="color:white; text-align:right;">
          <button class="btn btn-primary" :disabled="page.length == (current_page+1)" :style="{display:page[current_page].disableNext?'none':'initial'}"  @click="nextPage">NEXT</button> 
           </h3>
         </div>
       </div>
      </div>
</template>
<style scope>
.social label{
	margin:5px;
}
</style>
<script>
Vue.component("upload-media",require("./whatsapp/uploadMedia.vue").default);
Vue.component("order-confirmation",require("./whatsapp/orderConfirmationPage.vue").default);
Vue.component("user-registration",require("./whatsapp/userRegistration.vue").default);
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

import MyFormData from "./whatsapp/uploadFiles/uploadFile";
export default {
  data () {
    return {
      orderId:0,
      addressId:0,
    balance: 0,
    current_page:0, 
    user_id:"",
    isOtpVerified:false,
    agent_type:"loggedout",
    download_pdf:{status:false,amount:200},
    colors: [
      { id: 0, hex: "#f12711", disabled: false },
      { id: 1, hex: "#f5af19", disabled: false }
    ],
    page:[
      {index:0,name:"FRONT COVER DESIGN",active:true,disableNext:false},
      {index:1,name:"INSIDE PAGE BACKGROUND",active:false,disableNext:false},
      {index:2,name:"UPLOAD EXPORTED .TXT FILE",active:false,disableNext:true},
      {index:3,name:"Order Confirmation",active:false,disableNext:true},
      {index:4,name:"Address and Payment",active:false,disableNext:true},
    ],
    source:"whatsapp",
    navigation:{
      cover:true,
    },
    prevData:{
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
        paper_type:0,
        binding_type:"",
        no_of_compies:"",
        from:"",
        to:"",
        total_messages:"",
        number_of_photos:"",
        print_both_messasges_photos:false,
        printing_cost:20,
        binding_cost:200,
        total_pages:0,
        paper_cost:20,
        delivery_charges:20,
        gst:20,
    perPageMessages:15,
    perPageImages:2,
      },
    prevColorData:{
        type:"DEFAULT",
        backgroundColor:"green",
        backgroundGradientColor:this.gradient,
        backgroundImage:"/images/default.png"
      },
    user:{
        address:{
          full_name:"",
          mobile_number:"",
          address:"",
          city:"",
          state:"",
          landmark:"",
        },
        full_name:"",
        mobile_number:"",
        email:"",
        whatsapp_number:""
    },
      mediaData:{
        pdf:"",
      }
    }
  },

  mounted () {
    let _this=this;
    axios.get("/check-login-status")
    .then(response=>{
      if(response.data.status=="loggedin"){
        _this.user_id=response.user_id;
        _this.isOtpVerified=true;
        _this.agent_type="loggedin";
      }
    });
    if(window.location.href.split("/")[4]!=undefined){
      this.getDataById(window.location.href.split("/")[4]);
    }
  },
  methods: {
 
      getDataById (id) {
           let _this=this;
      axios.get("/api/users/whatsapp/edit?order_id="+id)
      .then(response => {
        console.log(response);
       _this.prevData=JSON.parse(response.data.orderData.cost_and_cover_details);
       _this.page.map(function(val,i){
            val.disableNext=false;
       })
       _this.agent_type="edit";
       _this.user.address={
         full_name:response.data.address.full_name,
         mobile_number:response.data.address.mobile_number,
         address:response.data.address.address,
         state:response.data.address.state,
        landmark:response.data.address.landmark,
        city:response.data.address.city,
         }
         _this.orderId=response.data.orderData.id;
         _this.addressId=response.data.address.id;
         if(response.data.background_data!=null){
         _this.colorData=response.data.background_data;
         }
      })
    },
    nextPage(){
      
    console.log(this.agent_type);
      // console.log({page:this.page.length,currentPage:this.current_page})
      if(this.page.length > (this.current_page+1)){
      this.page[this.current_page].active=false;
      this.current_page++;
      this.page[this.current_page].active=true;
      }
    },
    previousPage(){
        if(this.current_page>0){
      this.page[this.current_page].active=false;
      this.current_page--;
      this.page[this.current_page].active=true;
      }
    },
    firstPersonFile(event){
          // const file = event.target.files[0];
     var reader = new FileReader()
     var data="";
    reader.readAsDataURL(event.target.files[0])
    reader.onload = ()=> {
             this.prevData.first_person.image=reader.result;
                     };
                    
        },
    secondPersonFile(event){
          // const file = event.target.files[0];
     var reader = new FileReader()
    reader.readAsDataURL(event.target.files[0])
    reader.onload = ()=> {
              this.prevData.second_person.image=reader.result;
                     };
        },
      generatePdf(data,loader){
    // let files = data.map((obj) => obj.file);
    let _this=this;
    let form = new MyFormData({files: data,"coverDetails":this.prevData,"cover":this.prevColorData,"background":this.prevColorData});
    form.post('/whatsapp/generate-pdf/')
        .then(function(response){
            loader.hide();
            _this.prevData.total_pages=response.data.total_pages;
            _this.prevData.total_messages=response.data.total_messages;
            _this.prevData.number_of_photos=response.data.total_images;
            _this.mediaData.pdf=response.data.pdfLink;
            _this.page[_this.current_page].active=false;
            _this.page[++_this.current_page].active=true;

        })
        .catch(function(){
          
            loader.hide();
        });

        },
    backgroundImage(event){

      if(!this.isOtpVerified){
        
      }
          // const file = event.target.files[0];
     var reader = new FileReader()
    reader.readAsDataURL(event.target.files[0])
    reader.onload = ()=> {
              this.data.backgroundImage=reader.result;
                     };
        },
        placeOrder(){
        this.page[this.current_page].active=false;
        this.page[++this.current_page].active=true;
        },
    updateOrder(){
      this.prevData.first_person.image="";
      this.prevData.second_person.image="";
     let data={
                    pdf_link:this.mediaData.pdf,
                    source:this.source,
                    address:this.user.address,
                    colorData:this.prevColorData,
                    orderData:this.prevData,
                    net_cost:this.net_cost,
                    grand_total:this.grand_total,
                    orderId:this.orderId,
                    addressId:this.addressId,
                    download_pdf:this.download_pdf.status
                };
  axios.post('/api/users/whatsapp/update-order/'+this.orderId, data)
                .then(function (response) {
                      if(response.status==200){
                        
          Vue.$toast.open('Order Updated Successfully  !');
          window.location.replace("/users/customer-orders");
                      }
                })
                .catch(function (error) {
                   console.log(error);
                });
    },

    payButton(){
     let data={
                    userData: this.user,
                    pdf_link:this.mediaData.pdf,
                    source:this.source,
                    colorData:this.prevColorData,
                    orderData:this.prevData,
                    isOtpVerified:true,
                    net_cost:this.net_cost,
                    grand_total:this.grand_total,
                    download_pdf:this.download_pdf.status
                };
var options = {
    // "key": "rzp_live_twYFYz1HMdEt0j", // Enter the Key ID generated from the Dashboard
    "key":"rzp_test_kBsgW4esoGvxx8",
    "amount": parseInt(this.grand_total), // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": "PS",
    "description": "PS",
    "image": "https://cdn.razorpay.com/logos/FNMb7LtPw5sqZd_medium.png",
    // "order_id": "order_9A33XWu170gUtm", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        // alert(response.razorpay_payment_id);
        // alert(response.razorpay_order_id);
        // alert(response.razorpay_signature)
        axios.post('/create-order', data)
                .then(function (response) {
                  window.location.replace("/users/customer-orders");
                })
                .catch(function (error) {
                   console.log(error);
                });
    },
    "prefill": {
        "name": this.user.full_name,
        "email": this.user.email,
        "contact": this.user.mobile_number
    },
    "notes": {
        "address": this.user.address.address
    },
    "theme": {
        "color": "#F37254"
    }
  };
  var rzp1 = new Razorpay(options);
  rzp1.open();
    }
  },
  computed:{
      gradient() {
      let colors = "linear-gradient(45deg";
      this.colors.forEach(function(e) {
        colors += "," + e.hex;
      });
      colors += ")";
      return colors;
    },
    net_cost(){
      
      if(this.download_pdf.status){
        return (this.prevData.printing_cost+this.total_pages)+this.download_pdf.amount+(this.prevData.paper_cost*this.total_pages)+this.prevData.binding_cost+this.prevData.delivery_charges;
      }

      return (this.prevData.printing_cost+parseInt(this.prevData.paper_type)+this.total_pages)+(this.prevData.paper_cost*this.total_pages)+this.prevData.binding_cost+this.prevData.delivery_charges;
    },
    gst(){
      let gst=(this.net_cost/100)*5.25;
      console.log(gst);
     return gst;
    },
    grand_total(){
      let grand_total=this.net_cost+this.gst;
      return grand_total;
    },
    background(){
      // return this.data.type=='DEFAULT' || this.data.type=='IMAGE'?`url('${this.data.backgroundImage}`:
      //                     (this.data.type=='SOLID'?this.data.backgroundColor:
      //                         (this.data.backgroundGradientColor)
      //                       )

      if(this.prevColorData.type=="SOLID")
      {
        
        return this.prevColorData.backgroundColor;
      }
      else if(this.prevColorData.type=="GRADIENT"){
        return this.prevColorData.backgroundGradientColor;
      }
      // else if(this.data.type=="IMAGE"){
      //   // return this.data.backgroundImage;
      //   return "blue";
      // }
      else{
        return `url("${this.prevColorData.backgroundImage}")`;
      }
    },
    total_pages(){
      // if(this.total_pages>=this.prevData.total_pages){
      //   return this.total_pages;
      // }
      var per_page_messages=this.prevData.total_messages/this.prevData.perPageMessages;
      let messages_pages=Math.floor(per_page_messages);
      // console.log({messages_pages,per_page_messages,"total_messages":this.prevData.total_messages});
       if(!this.prevData.print_both_messasges_photos){
       return messages_pages;
       }
       let images_pages=(this.prevData.number_of_photos/this.prevData.perPageImages);
        let total_pages=messages_pages+images_pages
         return Math.floor(total_pages);
    }
    // total_pages(){
    //   let messages_pages=Math.floor(this.prevData.total_pages/this.perPageMessages);
    //    if(this.prevData.print_both_messasges_photos){
    //    return messages_pages;
    //    }
    //    let images_pages=(this.prevData.total_messages/this.perPageImages);
    //     let total_pages=messages_pages-images_pages
    //      return Math.floor(total_pages);
    // }
    // disableNext(){
    //   if(this.current_page<1){
    //     return true;
    //   }
    //   return false;
    //   // return this.page[this.current_page].disableNext
    // }
  },
  watch:{
       source: function(val, oldVal) {
        },
        colors:{
      deep:true,
      handler(val,oldVal){
          let colors = "linear-gradient(45deg";
      this.colors.forEach(function(e) {
        colors += "," + e.hex;
      });
      colors += ")";
      this.prevColorData.backgroundGradientColor=colors;
      }
    }
        
    }
    
    
}
</script>
