<template>
  <div @click="onClick">
   <div class="row">
      <div v-show="errorForm" class="col-md-12 col-lg-12 col-sm-12 col-xs-12">

        <div class="alert alert-danger">
            <!-- <strong>Error</strong> Validation error caught while submitting form -->
            <ul v-for="(value, key, index) in errorData">
              <li>{{value}}</li>
            </ul>
        </div>
        </div>
     <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
        <img :src="rowData.image" :alt="rowData.name" width="100%" class="img-thumbnail">
        <br>
          <dropzone v-on:vdropzone-queue-complete="complete()" ref="documentDropzone" id="dropzone" :options="dropzoneOptions"></dropzone>
            <div class="col-xs-12 divider text-center">
                <div class="col-xs-12 col-sm-6 emphasis">              
                    <p><small> {{totalFiles}} Files uploaded</small></p>
                </div>
                <div v-if="totalFiles > 0" class="col-xs-12 col-sm-6 emphasis">              
                    <p><small><a href="javascript:void(0)" @click="fetchFiles()">View Files</a></small></p>
                </div>
            </div>

     </div>
    <div class="col-md-8 col-lg-8 col-sm-8 col-xs-8">
            <div class="col-sm-12">

                    <div class="col-xs-12 col-sm-12">
 <table class="table table-striped">
          <tbody>
             <tr>
                <td colspan="1">
                   <form class="well form-horizontal">
                      <fieldset>

                         <div class="form-group">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Membership</span>
                                  <select v-model="editData.membership" class="selectpicker form-control" name="membership" :disabled="!edit">
                                     <option value="General" :selected="(rowData.membership == 'General') ? 'selected' : ''">General</option>
                                     <option value="Regular" :selected="(rowData.membership == 'Regular') ? 'selected' : ''">Regular</option>
                                     <option value="Executive" :selected="(rowData.membership == 'Executive') ? 'selected' : ''">Executive</option>
                                  </select>
                               </div>

                         </div>

                         <div class="form-group" v-if="editData.membership == 'Executive'">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Membership</span>
                                  <select v-model="editData.executive_class" class="selectpicker form-control" name="executive_class" :disabled="!edit">
                                     <option value="Super" :selected="(rowData.executive_class == 'Super') ? 'selected' : ''">Super</option>
                                     <option value="Superior" :selected="(rowData.executive_class == 'Superior') ? 'selected' : ''">Superior</option>
                                     <option value="Dynamic" :selected="(rowData.executive_class == 'Dynamic') ? 'selected' : ''">Dynamic</option>
                                     <option value="Tycoon" :selected="(rowData.executive_class == 'Tycoon') ? 'selected' : ''">Tycoon</option>
                                  </select>
                               </div>

                         </div>

                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Name</span><input v-model="editData.name" name="name" placeholder="Name" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Spouse's Name</span><input v-model="editData.father_name" name="father_name" placeholder="Spouse's Name" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                          <div class="form-group">
                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Gender</span>
                                  <select v-model="editData.gender" class="selectpicker form-control" name="executive_class" :disabled="!edit">
                                     <option value="Male" :selected="(rowData.gender == 'Male') ? 'selected' : ''">Male</option>
                                     <option value="Female" :selected="(rowData.gender == 'Female') ? 'selected' : ''">Female</option>
                                  </select>
                               </div>
                          </div>


                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Phone</span><input v-mask="'###########'" v-model="editData.phone_number" name="phone" placeholder="Phone" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Mobile</span><input v-mask="'###########'" v-model="editData.mobile_number" name="mobile_number" placeholder="Mobile" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Mobile 2</span><input v-mask="'###########'" v-model="editData.mobile_number2" name="mobile_number2" placeholder="Mobile 2" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Emergency Number</span><input v-mask="'###########'" v-model="editData.emergency_number" name="emergency_number" placeholder="Emergency Number" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Relation</span><input v-model="editData.relation" name="relation" placeholder="Relation" class="form-control"  type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Email</span><input v-model="editData.email" name="email" placeholder="Email" class="form-control" type="text" :disabled="!edit"></div>

                         </div> 

                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">Address</span><input v-model="editData.address" name="address" placeholder="Address" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Postal Address</span><input v-model="editData.postal" name="postal" placeholder="Postal Address" class="form-control" type="text" :disabled="!edit"></div>

                         </div>


                      </fieldset>
                   </form>
                </td>
                <td colspan="1">
                   <form class="well form-horizontal">
                      <fieldset>
                         <div class="form-group">
                               <div class="input-group"><span class="input-group-addon">NT Number</span><input v-mask="'#######-#'" v-model="editData.ntn" name="ntn" placeholder="NT Number" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Nationality</span>
                                  <select v-model="editData.nationality" class="selectpicker form-control" name="nationality" :disabled="!edit">
                                     <option value="Single" :selected="(rowData.nationality == 'Single') ? 'selected' : ''">Single</option>
                                     <option value="Dual" :selected="(rowData.nationality == 'Dual') ? 'selected' : ''">Dual</option>
                                  </select>
                               </div>

                         </div>

                        <!-- If nationality == single -->
                          <template v-if="editData.nationality == 'Single'">
                            
                         <div class="form-group" v-if="editData.cnic != ''">

                               <div class="input-group"><span class="input-group-addon">CNIC</span><input v-mask="'#####-#######-#'" v-model="editData.cnic" name="cnic" placeholder="Pakistan Based CNIC" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                          </template>

                         <!-- If nationality == dual -->
                        <template v-if="editData.nationality == 'Dual'">
                         <div class="form-group" >

                               <div class="input-group"><span class="input-group-addon">Overseas CNIC</span><input v-mask="'######-######-#'" v-model="editData.overseas_cnic" name="overseas_cnic" placeholder="Overseas CNIC" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Overseas Country</span><input v-model="editData.overseas_country" name="overseas_country" placeholder="Overseas Country" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         </template>
                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">DOB</span><input v-model="editData.dob" name="dob" placeholder="DOB" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Province</span>
                                  <select class="selectpicker form-control" name="province" :disabled="!edit">
                                     <option v-model="editData.province" value="Sindh" :selected="(rowData.province == 'Sindh') ? 'selected' : ''">Sindh</option>
                                     <option value="Punjab" :selected="(rowData.province == 'Punjab') ? 'selected' : ''">Punjab</option>
                                     <option value="Balochistan" :selected="(rowData.province == 'Balochistan') ? 'selected' : ''">Balochistan</option>
                                     <option value="Kpk" :selected="(rowData.province == 'Kpk') ? 'selected' : ''">Kpk</option>
                                     <option value="Gilgit" :selected="(rowData.province == 'Gilgit') ? 'selected' : ''">Gilgit</option>
                                  </select>
                               </div>

                         </div>
                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">City</span><input v-model="editData.city" name="city" placeholder="City" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Passport</span><input v-model="editData.passport_number" name="passport_number" placeholder="Passport Number" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Issue Date</i></span><input v-mask="'##-##-####'" v-model="editData.issue_date" name="issue_date" placeholder="Issue Date" class="form-control" type="text" :disabled="!edit"></div>

                         </div>
                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Expiry Date</span><input v-mask="'##-##-####'" v-model="editData.expiry_date" name="expiry_date" placeholder="Expiry Date" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Member Since</span><input v-mask="'####'" v-model="editData.since" name="since" placeholder="Member Since" class="form-control" type="text" :disabled="!edit"></div>

                         </div>

                         <div class="form-group">

                               <div class="input-group"><span class="input-group-addon">Member Image</span><input @change="processFile($event)" accept="image/*" name="image" placeholder="Image" class="form-control" type="file" :disabled="!edit" ref="fileInput"></div>

                         </div>

                      </fieldset>
                   </form>
                </td>
             </tr>
          </tbody>
       </table>

                      <div class="btn-group btn-group-justified">
                        <a class="btn btn-default" role="button" @click="editForm()">{{ (edit) ? 'Cancel' : 'Edit'}}</a>
                        <a class="btn btn-default" role="button" @click="submitEditForm()">Save</a>
                      </div>

                </div>  


                             
            </div>            
            <div class="col-xs-12 divider text-center">
                <div class="col-xs-12 col-sm-12 emphasis">
                    <h2><strong>  </strong></h2>                    
                    <p><small>Upload Documents</small></p>
                    <button class="btn btn-success btn-block" @click="uploadDocs"><span class="fa fa-plus-circle"></span> Upload </button>
                </div>
            </div>               
    </div>
  </div>
  <div class="row" v-show="showGallery">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <viewer :images="images" @inited="inited" class="viewer" ref="viewer">
          <template slot-scope="scope">
            <img v-for="src in scope.images" :src="src" :key="src">
          </template>
        </viewer>
    <a href="javascript:void(0)" class="pull-right" @click="downloadDocs(rowData.id)"><i class="fa fa-download"></i> Download All</a>
    </div>
  </div>
  </div>
</template>

<script>
import { Validator } from 'vee-validate'
import axios from 'axios'
import {mask} from 'vue-the-mask'
import 'viewerjs/dist/viewer.css'
import vue2Dropzone from 'vue2-dropzone'
import Viewer from "v-viewer/src/component.vue"
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
  components: {dropzone: vue2Dropzone, Viewer},
  directives: {mask},
  props: {
    rowData: {
      type: Object,
      required: true
    },
    rowIndex: {
      type: Number
    }
  },
  data(){
    return {
        errorForm: false,
        errorData: '',
        editData:{
          membership: this.rowData.membership,
          executive_class: this.rowData.executive_class,
          name: this.rowData.name,
          father_name: this.rowData.father_name,
          phone_number: this.rowData.phone_number,
          mobile_number: this.rowData.mobile_number,
          mobile_number2: this.rowData.mobile_number2,
          emergency_number: this.rowData.emergency_number,
          relation: this.rowData.relation,
          email: this.rowData.email,
          gender: this.rowData.gender,
          address: this.rowData.address,
          postal: this.rowData.postal,
          since: this.rowData.since,
          ntn: this.rowData.ntn,
          nationality: this.rowData.nationality,
          cnic: this.rowData.cnic,
          overseas_cnic: this.rowData.overseas_cnic,
          overseas_country: this.rowData.overseas_country,
          dob: this.rowData.dob,
          province: this.rowData.province,
          city: this.rowData.city,
          passport_number: this.rowData.passport_number,
          issue_date: this.rowData.issue_date,
          expiry_date: this.rowData.expiry_date,
        },
        edit: false,
        images: [],
        totalFiles : 0,
        showGallery: false,
        dropzoneOptions: {
          url: '/businessmen/'+this.rowData.id,
          thumbnailWidth: 105,
          addRemoveLinks: true,
          autoProcessQueue: false,
          acceptedFiles: 'image/*',
          maxFilesize: 4,
          maxFiles: 2,
          dictDefaultMessage: '<p><i class="fa fa-caret-right text-primary"></i> Drop Document Here</p>',
          headers: {
            'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
        },
      },
    }
  },
  watch: {
    showGallery: function (val) {

            if (val) {

                this.images = []
                axios.get('/businessmen/fetchhirarcy/'+this.rowData.id)
               .then(res => {

                      for (var i = 0; i < res.data.length; i++) {
                          this.images.push(res.data[i])
                      }

                      this.showGallery = true

               })
            }
    },
  },
  methods: {
      downloadDocs(id){
          axios.get('/businessmen/download/'+id,{responseType: 'blob'})
               .then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', this.rowData.bmr_number + '.zip');
                document.body.appendChild(link);
                link.click();
        });
      },
      submitEditForm(){

        if (this.edit) {
            axios.put('/businessmen/'+this.rowData.id, {
              params: this.editData
            }).then((response) => {
                      this.errorForm = false
                      this.edit = false

                      this.$events.fire('filter-set', 15)

                              this.$notify({
                              group: 'success',
                              type: 'success',
                              title: 'Success',
                              duration: 4000,
                              text: 'Record Updated of '+this.rowData.bmr_number
                              })

            },
            (error) => {
              this.errorData = error.response.data
              this.errorForm = true

                  this.$notify({
                    group: 'success',
                    type: 'error',
                    title: 'Validation Error',
                    duration: 4000,
                    text: 'One or more field caught on validation'
                  })

              
            })
        }else{
          alert('Edit form before submitting')
        }

      },
      processFile(event) {


            var types = ['image/png', 'image/jpeg', 'image/jpg']
            var fileType = event.target.files[0].type

            if (event.target.files[0].size > 2000096) {
                alert('Image must not be greater than 2 MB')
                this.$refs.fileInput.value = ''
                event.target.files[0] = ''
            }else if (!types.includes(fileType)){
                alert('Selected file is not allowed')
                this.$refs.fileInput.value = ''
                event.target.files[0] = ''
            }else{

                var reader = new FileReader()
                reader.readAsDataURL(event.target.files[0])
                reader.onload = () => {

                  Vue.set(this.editData, 'file', reader.result)
                }
            }

        
      },
      editForm(){
          (this.edit) ? this.edit = false : this.edit = true
      },
      inited (viewer) {
      this.$viewer = viewer
      },
      show () {
      this.$viewer.show()
      },
    fetchFiles(){

          (this.showGallery) ? this.showGallery = false : this.showGallery = true

    },
    complete(file, xhr, formData){
        var fileInstance = this.$refs.documentDropzone.dropzone
            fileInstance.removeAllFiles()

                this.$events.fire('filter-set', 15)
                  this.$notify({
                    group: 'success',
                    type: 'success',
                    title: 'Success',
                    duration: 4000,
                    text: 'Files uploaded successfully.'
                  })

    },
    uploadDocs(){

        var fileInstance = this.$refs.documentDropzone.dropzone
        if (fileInstance.getQueuedFiles() != '') {
            fileInstance.processQueue()

        }else{
            alert('Please select file to upload')
        }

    },
    onClick (event) {
      // console.log('my-detail-row: on-click', event.target)
    },
    addDocument(){

    }
  },
  mounted(){

      axios.get('/businessmen/counthirarcy/'+this.rowData.id)
      .then(res => {
        this.totalFiles = res.data
      })
  },
}
</script>

<style>
  .profile 
{
    min-height: 355px;
    display: inline-block;
    }
figcaption.ratings
{
    margin-top:20px;
    }
figcaption.ratings a
{
    color:#f1c40f;
    font-size:11px;
    }
figcaption.ratings a:hover
{
    color:#f39c12;
    text-decoration:none;
    }
.divider 
{
    border-top:1px solid rgba(0,0,0,0.1);
    }
.emphasis 
{
    border-top: 4px solid transparent;
    }
.emphasis:hover 
{
    border-top: 4px solid #1abc9c;
    }
.emphasis h2
{
    margin-bottom:0;
    }
span.tags 
{
    background: #1abc9c;
    border-radius: 2px;
    color: #f5f5f5;
    font-weight: bold;
    padding: 2px 4px;
    }
.dropdown-menu 
{
    background-color: #34495e;    
    box-shadow: none;
    -webkit-box-shadow: none;
    width: 250px;
    margin-left: -125px;
    left: 50%;
    }
.dropdown-menu .divider 
{
    background:none;    
    }
.dropdown-menu>li>a
{
    color:#f5f5f5;
    }
.dropup .dropdown-menu 
{
    margin-bottom:10px;
    }
.dropup .dropdown-menu:before 
{
    content: "";
    border-top: 10px solid #34495e;
    border-right: 10px solid transparent;
    border-left: 10px solid transparent;
    position: absolute;
    bottom: -10px;
    left: 50%;
    margin-left: -10px;
    z-index: 10;
    }
.viewer img{
  width: 200px;
}
</style>