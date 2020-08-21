<template>
       <div >
              <h2 class="text-center" style="padding-top:20px;">IN PAGE BACKGROUND</h2 >
         <div class="row">
		<div class="col-lg-7 col-sm-2">
      
      <div class="col col-12">
        <div class="col col-12">
        <label>Paper Orientation</label>
        <select v-model="prevColorData.type" class="form-control">
          <option value="">CHOOSE BACKGROUND TYPE</option>
          <option  value="DEFAULT">Use Original WhatsApp Background</option>
          <option value="SOLID">Solid Color</option>
           <option value="GRADIENT">Gradient Color</option>
           <option value="IMAGE">Choose the Picture</option>
        </select>
          </div>
      </div>
 <div class="col col-12" >
        <div v-if="prevColorData.type=='SOLID'" class="col col-12" style="height:50px;">
        <label>Background Color</label>
    <!-- <div id="app"> -->
        <input type="color" style="background:none;" class="form-control" v-model="prevColorData.backgroundColor">
    <!-- </div> -->
          </div>
</div>


 <div class="col col-12">
        <div v-if="prevColorData.type=='GRADIENT'" class="col col-12">
        <label>Gradient Color</label>
    <div id="app">
     <div id="app" >
  <div class="container">
    <transition-group name="flip-list" tag="ul">
      <li v-for="(color,index) in colors" :key="color.id" :class="{shake : color.last}">
        <div class="move">
          <button class="up" style="color:black;" @click="up(index)" v-if="index>0"></button>
          <button class="down" style="color:black;" @click="down(index)" v-if="index<colors.length-1"></button>
        </div>
        <input type="color" v-model.trim="color.hex" maxlength="7"
        style="width:70px; height:30px;"
        @change="colorChanged"
        :class="{pin : color.disabled}" :disabled="color.disabled" 
        autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        <button @click="lockColor(index)" :class="{locked : color.disabled}"></button>

      </li>
    </transition-group>
    <div class="button-group">
      <div>
        <button @click="addColor" id="add"></button>
        <transition name="fade">
          <button @click="removeColor" id="remove" v-if="colors.length > 2"></button>
        </transition>
      </div>
      <button @click="generateGradient">random</button>
    </div>
  </div>
</div>
    </div>
          </div>
</div>




 <div class="col col-12">
        <div v-if="prevColorData.type=='IMAGE'" class="col col-12">
        <label>Choose Picture</label>
            <input type="file" class="form-control" v-on:change="backgroundImage">
          </div>
</div>

</div>


    <div class="col col-4" 
    

    :style="`background:${background}; margin-top:30px; padding-bottom:70px;`">
      <div class="col col-12 row" style="padding:10px; margin-left:10px;">
      <img src="/icon/whatsapp.svg" width="50px" style='filter:drop-shadow(rgba(255, 255, 255, 3.5) 0px 0px 10px);
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
         <h1 style="  text-align: center;
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
         <div class="col col-3">
       
         </div>
         <div class="col col-4 offset-1">
           <center>
          
           </center>
         </div>
         <div class="col col-3">
   
         </div>
       </div>
       <div class="col col-12 row">
         <div class="col col-6">
           <h3 style="color:white;">
           </h3>
         </div>
         <div class="col col-6">
             <h3 style="color:white; text-align:right;">
        
           </h3>
         </div>
       </div>



        
    </div>
  </div>
      </div>
</template>
    

<script>
// import { GradientPicker} from './GradientPicker/GradientPicker.vue';
 
Vue.component("gradient-picker",require("./GradientPicker/GradientPicker.vue").default); 
export default {
  name:"App",
  data () {
    return {
   
    id: 2,
    data:{
        type:"DEFAULT",
        backgroundColor:"green",
        backgroundGradientColor:this.gradient,
        backgroundImage:"/images/default.png"
      }
    };
  },
  computed: {
    // gradient() {
    //   let colors = "linear-gradient(45deg";
    //   this.colors.forEach(function(e) {
    //     colors += "," + e.hex;
    //   });
    //   colors += ")";
    //   return colors;
    // },
    // background(){
    //   // return this.data.type=='DEFAULT' || this.data.type=='IMAGE'?`url('${this.data.backgroundImage}`:
    //   //                     (this.data.type=='SOLID'?this.data.backgroundColor:
    //   //                         (this.data.backgroundGradientColor)
    //   //                       )

    //   if(this.data.type=="SOLID")
    //   {
        
    //     return this.data.backgroundColor;
    //   }
    //   else if(this.data.type=="GRADIENT"){
    //     return this.data.backgroundGradientColor;
    //   }
    //   // else if(this.data.type=="IMAGE"){
    //   //   // return this.data.backgroundImage;
    //   //   return "blue";
    //   // }
    //   else{
    //     return `url("${this.data.backgroundImage}")`;
    //   }
    // }
  },
  props: ["prevColorData",'colors','background','gradient','backgroundImage'],
  mounted () {
  },
  methods: {
    addColor() {
      this.colors.push({ id: this.id, hex: this.randomHex(), disabled: false });
      this.id++;
    },
    colorChanged(){
    },
    removeColor() {
      if (this.colors[this.colors.length - 1].disabled == false) {
        if (this.colors.length > 2) {
          this.colors.pop();
        }
      }
    },
    generateGradient() {
      for (let i = 0; i < this.colors.length; i++) {
        if (this.colors[i].disabled === false)
          this.colors[i].hex = this.randomHex();
      }
    },
    lockColor(index) {
      this.colors[index].disabled === true
        ? (this.colors[index].disabled = false)
        : (this.colors[index].disabled = true);
    },
    randomHex() {
      return (
        "#" +
        Math.random()
          .toString(16)
          .slice(2, 8)
      );
    },
    up(index) {
      if (index > 0) {
        let temp = this.colors[index];
        this.$set(this.colors, index, this.colors[index - 1]);
        this.$set(this.colors, index - 1, temp);
      }
    },
    down(index) {
      if (index < this.colors.length - 1) {
        let temp = this.colors[index];
        this.$set(this.colors, index, this.colors[index + 1]);
        this.$set(this.colors, index + 1, temp);
      }
    },
     onChange(attrs, name) {
      // this.color = { ...attrs };
      console.log(attrs)
    }
    // backgroundImage(event){
    //       // const file = event.target.files[0];
    //  var reader = new FileReader()
    // reader.readAsDataURL(event.target.files[0])
    // reader.onload = ()=> {
    //           this.data.backgroundImage=reader.result;
    //                  };
    //     }
  },
  watch:{
    //    data:{
    //      deep:true,
    //      handler(val, oldVal) {
    //        if(this.data.type=="DEFAULT"){
    //         this.data.backgroundImage="/images/default.png";
    //        }
    //      this.prevColorData=val;
        
    //     }
    // }
  }
}
</script>

<style src="vue-color-gradient-picker/dist/index.css" lang="css" ></style>

<style scoped>
@import url("https://fonts.googleapis.com/css?family=Lato");
@import url("https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");


body {
  margin: 0;
  height: 100vh;
  font-family: "Lato", sans-serif;
  font-weight: bold;
  font-size: 1.5rem;
  text-transform: uppercase;
}

#app {
  height: 100%;
  display: flex;
  transition: background-color 5s;
}

.container {
  margin: auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}

li {
  margin-bottom: 5px;
  display: flex;
  align-items: center;
  position: relative;
}

li > button {
  border-radius: 0px 10px 10px 0px;
}

.button-group {
  display: flex;
  justify-content: space-between;
  width: 100%;
}

.button-group > div > button {
  padding: 15px 20px;
}

.button-group button {
  border-radius: 10px;
}

button {
  transition: 0.25s;
}

input,
button {
  all: unset;
}

li > button::before {
  font-family: "FontAwesome";
  content: "\f09c";
}

.locked::before {
  content: "\f023";
}

input {
  display: inline;
  background: #ffffff;
  padding: 15px;
  max-width: 225px;
  /* 	padding-left:0px; */
  border-radius: 10px 0px 0px 10px;
  transition: color 0.25s, background 0.25s ease-out;
}

li > button,
.button-group button {
  background: #000000;
  color: #ffffff;
  padding: 15px;
  cursor: pointer;
  min-width: 25px;
  text-align: center;
}

.button-group button:hover {
  background: #ffffff;
  color: #000000;
}

li > button:hover::before {
  content: "\f023";
}

.locked:hover::before {
  content: "\f09c";
}

.pin {
  background: #000000;
  color: #ffffff;
  /* 	transition:0.25s; */
  animation-duration: 0.05s;
  /*   	animation-name: fillIn; */
}

ul li i {
  content: "#";
  padding: 15px;
  padding-right: 0px;
  background: #ffffff;
  border-radius: 10px 0px 0px 10px;
}

ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

#add::before {
  font-family: "FontAwesome";
  content: "\f067";
}

#remove::before {
  font-family: "FontAwesome";
  content: "\f068";
}

#remove::before {
  font-family: "FontAwesome";
  content: "\f068";
}

.up::before {
  font-family: "FontAwesome";
  content: " ";
}

.down::before {
  font-family: "FontAwesome";
  content: " ";
}

.move button {
  font-size: 1.25rem;
  cursor: pointer;
  height: 100%;
  width: 100%;
  text-align: center;
}

li .up::before {
  content: "\f062";
  opacity: 0;
}

li:hover .up::before,
li:hover .down::before {
  opacity: 1;
  transition: opacity 0.25s ease-out;
}

li .down::before {
  content: "\f063";
  opacity: 0;
}

.move button:hover {
  color: #ffffff;
}

.move {
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: absolute;
  right: calc(100%);
  height: 100%;
  width: 35px;
}

li.shake {
  animation-duration: 0.75s;
  animation-name: shake;
}

@keyframes shake {
  10%,
  90% {
    transform: translate3d(-1px, 0, 0);
  }

  20%,
  80% {
    transform: translate3d(2px, 0, 0);
  }

  30%,
  50%,
  70% {
    transform: translate3d(-4px, 0, 0);
  }

  40%,
  60% {
    transform: translate3d(4px, 0, 0);
  }
}

#credit {
  position: absolute;
  right: 10px;
  bottom: 10px;
  font-size: 0.8rem;
  text-transform: none;
}

#credit a {
  color: #000000;
}

.flip-list-move {
  transition: transform 0.25s;
}

.flip-list-leave-active {
  position: absolute;
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

</style>
