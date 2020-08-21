<template>

       <div >
          <h2 class="text-center" style="padding-top:20px;">Pay for the Order</h2 >
         <h2 class="text-center" style="color:red;">REVIEW YOUR ORDER</h2 >
       <div class="row">
		<div  :class="{'col-lg-7':prevData.orientation=='POTRIATE','col col-6':prevData.orientation=='LANDSCAPE'}">
      <div class="col col-12">
        <div class="col col-12">
        <label>Paper Size</label>
        <select v-model="prevData.size" class="form-control">
          <option value="">SELECT PAPER SIZE</option>
          <option value="A4">A4</option>
          <option value="Letter">Letter</option>
        </select>
          </div>
      </div>
      <div class="col col-12">
        <div class="col col-12">
        <label>Paper Orientation</label>
        <select v-model="prevData.orientation" class="form-control">
          <option value="">SELECT PAPER ORIENTATION</option>
          <option  value="POTRIATE">Potriate</option>
          <option value="LANDSCAPE">Landscape</option>
          
        </select>
          </div>
      </div>

       <div class="col col-12">
        <div class="col col-12">
        <label>Paper TYPE</label>
          <select v-model="prevData.paper_type" class="form-control">
              <option value="0">SELECT PAPER TYPE</option>
              <option  value="25">Deluxe (Medium Thickness-180 GSM)-Glossy Paper</option>
              <option  value="25">Deluxe (Medium Thickness-180 GSM)-Matte Paper</option>
              <option  value="30">Deluxe (High Thickness-240 GSM)- Glossy Paper</option>
              <option  value="30">Premium (Medium Thickness -180 GSM)-Glossy Paper</option>
              <option  value="35">Premium (High Thickness -240 GSM)-Glossy Paper</option>
              <option  value="50">Ultra Premium (High Thickness-240 GSM)-Glossy Paper</option>
              <option  value="50">Ultra Premium (High Thickness-240 GSM)-Matte Paper</option>
          </select>
          </div>
      </div>
    <div class="col col-12">
        <div class="col col-12">
        <label>BINDING TYPE</label>
        <select v-model="prevData.binding_type" class="form-control">
          <option value="">SELECT BINDING TYPE</option>
          <option  value="HARD_COVER">Hard Cover</option>
          <option  value="SOFT_COVER">Soft Cover</option>
        </select>
          </div>
      </div>

      <div class="col col-12 row">
        <div class="col col-12">  
          <label>Date Range</label>
        </div>
         <div class="col col-6">
           <label>From</label>
          <input type='date' v-model="prevData.from" class="form-control">
         </div>
         <div class="col col-6">
           <label>To</label>
             <input type='date' v-model="prevData.to" class="form-control">
         </div>
       </div>
        <div class="col col-12 row">
        
         <div class="col col-6">
           <label>Number of Text Chat For Printing</label>
          <input type='number' v-model="prevData.total_messages" class="form-control">
         </div>
         <div class="col col-6">
           <label>Number of Photos found for printing</label>
             <input type='number' v-model="prevData.number_of_photos" class="form-control">
         </div>
       </div>
       <div class="col col-12 row">
          <div class="col col-6">
         <label>Print Both <input type="checkbox" v-model="prevData.print_both_messasges_photos" class="form-control"></label>
          </div>
            <div class="col col-6">
              <label>Total Pages Messages For Printing</label>
             <h1>
                {{total_pages}}
             </h1>
              </div>
       </div>

        <div class="col col-12 row">
          <div class="col col-6">
         <label>Buy Pdf Copy <input type="checkbox" v-model="download_pdf.status" class="form-control"></label>
          </div>
            
       </div>

 </div>
    <div  :class="{'potriate col col-4':prevData.orientation=='POTRIATE','col col-6 landscape':prevData.orientation=='LANDSCAPE'}" >
      <div class="col col-12 row" style="padding:10px; margin-left:10px;">
      <img 
      class="margin-whatsapp"
      src="/icon/whatsapp.svg" width="50px" style='filter:drop-shadow(rgba(255, 255, 255, 3.5) 0px 0px 10px);
'>
      <h2 style="padding: 20px;
    color: green;
    text-shadow: 0px 1px 6px white;
    font-weight: bolder;
    font-size: 47px;
    ">WhatsApp</h2>
      </div>
       <div class="col col-12 row" style="padding:10px; margin-left:10px; ">
         <center>
         <h1
         class="margin-memories"
          style="  text-align: center;
    text-shadow: black 0px 5px 0px;
    color: white;
    padding-left: 28px;
    padding-bottom: 40px;
    padding-top: 20px;
    font-size: 50px;
}">MEMORIES</h1>

         </center>
       </div>
       <div class="col col-12 row">
         <div class="col col-3 margin-image"  >
           <img :src="prevData.first_person.image!=''?prevData.first_person.image:'/icon/male.png'" height="100" width="100" style="border-radius: 50%;
    border: 2px solid white;">
         </div>
         <div class="col col-4 offset-1">
           <center>
           <img src="/icon/arrows.svg" width="100">
           </center>
         </div>
         <div class="col col-3">
      <img :src="prevData.second_person.image!=''?prevData.second_person.image:'/icon/female.png'" height="100" width="100" style="border-radius: 50%;
    border: 2px solid white;">
         </div>
       </div>
    </div>

       <div class="col col-12 row">
         <div class="col col-6">
           <h3 style="color:white;">
           {{prevData.first_person.name}}
           </h3>
         </div>
         <div class="col col-6">
             <h3 style="color:white; text-align:right;">
           {{prevData.second_person.name}}
           </h3>
         </div>
       </div>
    <div class="col col-12 row">
     
      <div class="col col-6">
      <center>

      <h1>&ensp;</h1>
      <h1>&ensp;</h1>
      <h2>
        <span>Total no of Pages to<br>
              be Printed in a<br>
              Single Copy</span>
              <br>{{total_pages}}
        </h2>
      </center>
    </div>
      <div class="col col-6">
       <table class="table table-striped" style="width:100%;">
         <tr>
            <th style="width:50%;">Printing Cost</th>
            <td>{{prevData.printing_cost*total_pages}}</td>
          </tr>
           <tr>
            <th style="width:50%;">Binding Cost</th>
            <td>{{prevData.binding_cost}}</td>
          </tr>
          <tr>
            <th style="width:50%;">Paper Cost</th>
            <td>{{prevData.paper_cost*total_pages}}</td>
          </tr>
           <tr>
            <th style="width:50%;">Shipping & Delivery Charges</th>
            <td>{{prevData.delivery_charges}}</td>
          </tr>
          <tr>
            <th style="width:50%;">Net Cost</th>
            <td>{{net_cost}}</td>
          </tr>
          <tr>
            <th style="width:50%;">
              GST 5.25%</th>
            <td>{{gst}}</td>
          </tr>
          <tr>
            <th style="width:50%;">
              Grand Total Cost</th>
            <td>{{grand_total}}</td>
          </tr>
          <tr>
            <td colspan="2"><button class='btn btn-warning from-control col-12' @click="placeOrder">PLACE ORDER</button></td>
            </tr>          
       </table>
    </div>
</div>
       </div>
      </div>
</template>
    
<style scope>
.social label{
	cursor:pointer;
}
.potriate{
  background:radial-gradient(#067806ad, green); margin-top:30px; padding-bottom:70px;
}
.landscape{
  background:radial-gradient(#067806ad, green); margin-top:30px;padding-bottom:70px;
}
.landscape .margin-whatsapp{
  margin-left:18%;
}

.landscape .margin-image{
  margin-left:8%;
}

.landscape .margin-memories{
  margin-left:36%;
}
</style>
<script>
export default {
  props: ["prevData","firstPersonFile","secondPersonFile","placeOrder","total_pages","net_cost","grand_total","gst","download_pdf"],
  data () {
    return {
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
  computed:{
   
  },
  methods: {
    next(){
      location.replace(`/${this.source}`);
    }
  },
  watch:{
       data:{
         deep:true,
         handler(val, oldVal) {
         this.prevData=val;
        }
       }
    }
}
</script>
