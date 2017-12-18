<template>
<div class="image-upload-wrapper Image-upload">
    <div id="croppie"></div>
    <div id="upload-wrapper">
        <!-- <button class="btn btn-primary btn-sm" @click="modalVisible=true"> 
            <i class="fa fa-camera"></i> Upload Image
        </button> -->

        <div class="form-row mt-3" v-if="modalVisible">

            <div class="col-md-10">
                <div class="input-file">
                    <label for="exampleInputFile">Upload Photo</label>
                    <input type="file" id="upload-image exampleInputFile" class="form-control-file filestyle"  data-size="sm" data-btnClass="btn-primary" data-buttonBefore="true" v-on:change="setUpFileUploader">
                </div>
            </div>

            <div class="col-md-2" style="margin-top: 30px">
                <button  class="btn btn-success btn-sm btn-block"  @click="uploadFile">
                    <i class="fa fa-upload"></i> Upload
                </button>

                <!-- <button class="btn btn-warning white-text" @click="modalVisible=false">
                    <i class="fa fa-times"></i> Cancel
                </button> -->
            </div>        
        
        </div>

        <div class="form-row row mt-3 text-left" v-if="successUpload">
            <div class="col-md-12">
                 <div class="alert alert-success" role="alert">
                <h5 class="alert-heading">Uploaded done!</h5>
                <p>Image uploaded successfully</p>
                </div>
            </div>
        </div>



    </div>
</div>


</template>

<script>
import Croppie from 'croppie'

export default {
    props: ['imgUrl'],

    mounted() {
        this.$on('imageUploaded', function(imageData) {
            this.image = imageData
            this.croppie.destroy()
            this.setUpCroppie(imageData)
        })
        this.image = this.imgUrl
        this.setUpCroppie()
        this.getImage()
    },

    data() {
        return {
            modalVisible: true,
            croppie: null,
            image: null,
            imageResult: [],
            successUpload: false,
            errors: []
        }
    },

    methods: {
        getImage() {
            axios.get('/driver_rfid/public/getImage')
            .then(response => this.imageResult = response.data);
            setTimeout(this.getImage, 2000);

        },

        setUpCroppie() {
            let el = document.getElementById('croppie')
            this.croppie = new Croppie(el, {
                viewport: {width: 200, height: 200, type: 'circle'},
                boundary: {width: 220, height: 220},
                showZoomer: true,
                enableOrientation: true
            })

            this.croppie.bind({
                url: this.image
            })
        },

        setUpFileUploader(e) {
            let files = e.target.files || e.dataTransfer.files
            if(!files.length) {
                return
            }
            this.createImage(files[0])
        },

        createImage(file) {
            console.log(file)
            var image = new Image()
            var reader = new FileReader()
            var vm = this

            reader.onload = (e) => {
                vm.image = e.target.result
                vm.$emit('imageUploaded',  e.target.result)
            }

            reader.readAsDataURL(file)
        },

        uploadFile() {
            this.croppie.result({
                type: 'canvas',
                size: 'viewport'
            }).then(response => {
                this.image = response
                axios.post('/driver_rfid/public/driver-image', {avatar: this.image})
                .then(response => {
                    this.modalVisible = false
                    this.successUpload = true
                })
                .catch(error => this.erros = error.response.data)
            })
        }
    }


}
</script>

<style lang="scss">
    .Image-upload {

        .Modal {
            border-top: 1px solid #f4f4f4;
            margin-top: 10px;
            h4 {
                margin-bottom: 2px;
            }
        }

        div#upload-wrapper {
            text-align: center;
        }

        .input-file {
            text-align: left;
            width: 100%;
            margin: 0px auto;
            // margin-bottom: 20px;
        }
    }
</style>