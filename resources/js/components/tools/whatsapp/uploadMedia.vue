<template>
       <div ref="formContainer">
          <h2 class="text-center" style="padding-top:20px;">UPLOAD EXPORTED .TXT and MEDIA (ONLY IMAGES) FILE</h2 >
         <p class="text-center" >(This file you will get when you export your chat history in WhatsApp and Upload media files only when, you want pics + chat both to be printed
Don’t use whatsapp export with media option, upload whatsapp media
from your phone storage)</p >
       <div class="row">
		<div class="col-lg-12 col-sm-2">
      <div class="col col-12">
        <div class="col col-12">
        <label>Select Files</label>
          <input type="file" @change="analyzeMedia"  
            name="picture"
            id="new-file"
            class="custom-file-input"
            aria-label="picture"
            multiple>
             <label class="custom-file-label"  for="new-file">
              File Should be < 5 MB
              <span class="btn-primary"></span>
            </label>
            <label class="row" v-for="(file,index) in form.files" :key="index" :style="sizeToMbs(file.size)>allowedFileSize||!fileAllowed(file.type)?'color:red':''">
              {{file.name}} 
             &ensp;&ensp;&ensp; <label v-if="sizeToMbs(file.size)>allowedFileSize">Size Greater than 5 MB (It will not be considered) {{fileAllowed(file.type)}}</label>
              &ensp;&ensp;&ensp; <label v-if="!fileAllowed(file.type)">Only jpg,png,and txt files are allowed (It will not be considered)</label>
              </label>
              
         <input type="submit" class="btn btn-primary" :disabled="form.files.length<1"  @click="submit" value="SUBMIT MEDIA"> 
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
</style>
<script>
  import MyFormData from "./uploadFiles/uploadFile";
  import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // Init plugin
    Vue.use(Loading);
export default {
  
  props: ["generatePdf"],
  data () {
    return {
        fullPage:false,
               form: new MyFormData({contract_id: 5, files: []}),
               allowedFileSize:5
    };
  },
  mounted () {
  
  },
  methods: {
    next(){
      location.replace(`/${this.source}`);
    },
     analyzeMedia(event) {
                for (let file of event.target.files) {
                    try {
                        let reader = new FileReader();
                        reader.readAsDataURL(file); // Not sure if this will work in this context.
                        this.form.files.push(file);
                          console.log(this.form.files);
                    } catch {}
                }
            },
      sizeToMbs(size){
        return Math.ceil(size/(1024*1024));
      },
      fileAllowed(type){
        if(type=="image/jpeg" || type=="image/png" || type=="text/plain"){
            return true;
        }
      },
      submit(){
         let loader = this.$loading.show({
                  // Optional parameters
                  container: this.$refs.formContainer,
                  canCancel: false,
                  onCancel: this.onCancel,
                });
        var _instance=this;
            this.form.files.map(function(file,index){
                  if(!_instance.fileAllowed(file.type) || _instance.sizeToMbs(file.size)>_instance.allowedFileSize){
                    _instance.$delete(_instance.form.files,index);
                  }
            });
          if(this.form.files.length<1){
            return;
          }
               this.generatePdf(this.form.files,loader);
            },
            
  },
  watch:{
       prevData:{
         deep:true,
         handler(val, oldVal) {
         this.data=val;
        }
       }
    }
}
</script>
