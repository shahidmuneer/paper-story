<template>
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

<!-- <div id="credit">Inspired by <a href="https://codepen.io/kowlor/full/apMbYJ/">Malik Dellidj</a></div> -->
</template>

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

<script>

// API('papMew')

export default{
    props:["backgroundGradientColor"],
  data(){
      
      return  {
    colors: [
      { id: 0, hex: "#f12711", disabled: false },
      { id: 1, hex: "#f5af19", disabled: false }
    ],
    id: 2
  };
  
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
    }
  },
  computed: {
    gradient() {
      let colors = "linear-gradient(45deg";
      this.colors.forEach(function(e) {
        colors += "," + e.hex;
      });
      colors += ")";
      this.backgroundGradientColor=colors;
      console.log(this.backgroundGradientColor)
      return colors;
    }
  },
  watch:{
    
    
  }
};

</script>