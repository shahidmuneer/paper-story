<template>

       <div >
          <h2 class="text-center" style="padding-top:20px;">FRONT COVER DESIGN</h2 >
         <h2 class="text-center" style="color:red;">({{prevData.orientation}})</h2 >
       <div class="row">
		<div  :class="{'col-lg-7':prevData.orientation=='POTRIATE','col col-6':prevData.orientation=='LANDSCAPE'}">
      
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
        <label>Paper Size</label>
        <select v-model="prevData.size" class="form-control">
          <option value="">SELECT PAPER SIZE</option>
          <option :value="prevData.orientation=='POTRIATE'?'A4':'A4-L'">A4</option>
          <option :value="prevData.orientation=='POTRIATE'?'Letter':'Letter-L'">Letter</option>
          
        </select>
          </div>
      </div>
 <div class="col col-12 row">
        <div class="col col-6 ">
        <label>First Person Image</label>
        <input type="file" @change="firstPersonFile" class="form-control">
          </div>
          <div class="col col-6">
        <label>Second Person Image</label>
        <input type="file" @change="secondPersonFile" class="form-control">
          </div>
</div>
 <div class="col col-12 row">
        <div class="col col-6">
        <label>First Person Name</label>
       <input type="text" class="form-control" v-model="prevData.first_person.name">
        </div>
        <div class="col col-6">
        <label>Second Person Name</label>
       <input type="text" class="form-control" v-model="prevData.second_person.name">
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
  
  props: ["prevData","firstPersonFile","secondPersonFile"],
  data () {
    return {
    data:{
      orientation:"POTRIATE",
    size:"",
    first_person:{
      name:"",
      image:""
    },
    second_person:{
      name:"",
      image:""
    }
    }
    };
  },
  mounted () {
  
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
