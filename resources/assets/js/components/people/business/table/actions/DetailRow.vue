<template>
  <div @click="onClick">
   <div class="row">
   <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">

              <div class="panel-group">
                <div class="panel panel-accordion panel-accordion-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a class="accordion-toggle" href="javascript:void(0)" aria-expanded="true" @click="toggler('toggle1')">
                        <i class="fa fa-star"></i> Show Businessmen related with this Business <i v-show="toggle1Loading" class="fa fa-spinner fa-spin"></i>
                      </a>
                    </h4>
                  </div>
                  <div v-show="toggle1" class="accordion-body" aria-expanded="true" style="">
                    <div class="panel-body">



                      <div class="timeline timeline-simple changelog">
            <div class="tm-body" v-for="(p, index) in partners">
              <ol class="tm-items">

                <li>
                <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2 text-center">
                  <img :src="p.image" width="80%">
                </div>
                <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">

                  <div class="tm-box">
                    <h4>{{p.date}}</h4> – <span class="release-date">Member Since {{ dateConverter(p.date) }}</span>
                    <ul class="list-unstyled">
                      <li><span class="label label-primary">Name</span> - {{p.name}}</li>
                      <li><span class="label label-primary">BMR</span> - {{p.bmr_number}}</li>
                      <li><span class="label label-primary">Designation</span> - {{p.designation}} <popper trigger="click" :options="{placement: 'bottom'}">

                        <div class="popper">

                            <form class="form-inline" @submit.prevent="editDesignation('designationForm', p.id)">
                              <div class="form-group">
                                <label for="designation">Designation:</label>
                                <input type="text" key="designation" name="designation" v-model="designation" class="form-control">
                              </div>
                              <button type="submit" class="btn btn-primary"><i v-show="submitIndex == 'designation'" class="fa fa-circle-o-notch fa-spin"></i> Submit</button>
                            </form>
                             <span class="customError">{{ message }}</span>

                        </div>

                      <i class="fa fa-edit" slot="reference"></i>
                      </popper>

                      </li>

                      <li><span class="label label-primary">Personal Email</span> - {{p.email}}</li>

                      <template v-for="(e, i) in p.businessemails">

                      <li>

                      <span class="label label-primary">Business Email</span> - {{e.email}} <popper trigger="click" :options="{placement: 'bottom'}">

                        <div class="popper">

                            <form class="form-inline" @submit.prevent="submitBusinessEmail('email-'+e.id, e.id)">
                              <div class="form-group">
                                <label for="email">Business Email:</label>
                                <input type="email" key="email" name="email" v-model="email" class="form-control">
                              </div>
                              <button type="submit" class="btn btn-primary"><i :key="e.id" v-show="submitIndex == e.id" class="fa fa-circle-o-notch fa-spin"></i> Submit</button>
                            </form>
                             <span class="customError">{{ message }}</span>

                        </div>

                      <i class="fa fa-edit" slot="reference"></i>
                      </popper>

                      </li>
                      </template>

                      <template v-if="p.businessemails.length < 2">


<!--   <popper trigger="click" :options="{placement: 'top'}">
    <div class="popper">
      Popper Content
    </div>

    <button slot="reference">
      Reference Element
    </button>
  </popper> -->

                        
                <popper trigger="click" :options="{placement: 'bottom'}">

                  <div class="popper">

                      <form class="form-inline" @submit.prevent="AddNewBusinessEmail('newemail-'+p.id, p.id, rowData.id)">
                        <div class="form-group" :class="{'has-error': errors.has('Newemail') }">
                          <label for="email">Business Email:</label>
                          <input type="text" key="Newemail" name="Newemail" v-validate="'required'" v-model="email" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><i :key="p.id" v-show="addIndex == p.id" class="fa fa-circle-o-notch fa-spin"></i> Submit</button>
                      </form>
                       <span class="customError">{{ errors.first('Newemail') }}</span>

                  </div>

                <li slot="reference"><a href="javascript:void(0)">Add Business Email</a></li>
                </popper>

                      </template>

                      </ul>
                  </div>
                </div>
                </li>

              </ol>
            </div>
          </div>
                    </div>
                  </div>
                </div>
                <div class="panel panel-accordion panel-accordion-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a class="accordion-toggle collapsed" href="javascript:void(0)" aria-expanded="false" @click="toggler('toggle2')">
                        <i class="fa fa-star"></i> Show Documents related with this Business <i v-show="toggle2Loading" class="fa fa-spinner fa-spin"></i>
                      </a>
                    </h4>
                  </div>
                  <div v-show="toggle2" class="accordion-body" aria-expanded="true">
                    <div class="panel-body">

    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <viewer :images="images" @inited="inited" class="viewer" ref="viewer">
          <template slot-scope="scope">
          <template v-for="src in scope.images">
            <img :src="src" :key="src">
          <!--<div class="col-md-3 col-xs-3 col-lg-3 col-sm-3 nopadding text-center">
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-danger btn-block" @click="removeDoc(src)">Delete</button>    
            </div> -->
          </template>
          </template>
        </viewer>
<!--     <a href="javascript:void(0)" class="pull-right" @click="downloadDocs(rowData.id)"><i class="fa fa-download"></i> Download All</a> -->
    </div>

                    </div>
                  </div>
                </div>
                  <div class="panel panel-accordion panel-accordion-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a class="accordion-toggle collapsed" href="javascript:void(0)" aria-expanded="false" @click="toggler('toggle3')">
                        <i class="fa fa-cogs"></i> Edit Data of {{rowData.br_number}} <i v-show="toggle3Loading" class="fa fa-spinner fa-spin"></i>
                      </a>
                    </h4>
                  </div>
                  <div v-show="toggle3" class="accordion-body" aria-expanded="true">
                    <div class="panel-body">
                        <div class="table-responsive col-md-12">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                <td colspan="1">
                   <form class="well form-horizontal">
                      <fieldset>

                         <div class="form-group" :class="{'has-error': errors.has('name') }">
                               <div class="input-group"><span class="input-group-addon">Name</span><input v-validate="'required'" name="name" placeholder="Name" class="form-control" type="text" v-model="editBusiness.name"></div>
                               <span class="customError">{{ errors.first('name') }}</span>

                         </div>

<!--                          <div class="form-group" :class="{'has-error': errors.has('designation') }">
                               <div class="input-group"><span class="input-group-addon">Designation</span><input v-validate="'required'"  name="designation" placeholder="Designation" class="form-control" type="text" v-model="editBusiness.designation"></div>
                               <span class="customError">{{ errors.first('designation') }}</span>

                         </div> -->

                         <div class="form-group" :class="{'has-error': errors.has('address') }">
                               <div class="input-group"><span class="input-group-addon">Address</span><input v-validate="'required'" name="address" placeholder="Address" class="form-control" type="text" v-model="editBusiness.address"></div>
                               <span class="customError">{{ errors.first('address') }}</span>

                         </div>

                         <div class="form-group" :class="{'has-error': errors.has('website') }">
                               <div class="input-group"><span class="input-group-addon">Website</span><input v-validate="'url:require_protocol'" name="website" placeholder="Website" class="form-control" type="text" v-model="editBusiness.website"></div>
                               <span class="customError">{{ errors.first('website') }}</span>

                         </div>

                          <div class="form-group" :class="{'has-error': errors.has('type') }">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Type</span>
                                  <select multiple="true" class="selectpicker form-control" name="type" v-model="editBusiness.type">

                                      <option value="Manufacturer">Manufacturer</option>
                                      <option value="Services">Services</option>
                                      <option value="Importer">Importer</option>
                                      <option value="Exporter">Exporter</option>
                                      <option value="Wholesaler">Wholesaler</option>
                                    

                                  </select>
                                  <span class="customError">{{ errors.first('group') }}</span>
                               </div>
                               <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                 
                                    Selected : <template v-for="gD in rowData.types">
                                      {{gD.type+', '}}
                                    </template>
                                      <br>
                                    New Selected : {{editBusiness.type}}
                               </div>

                         </div>

                          <div class="form-group" :class="{'has-error': errors.has('office') }">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Office</span>
                                  <select class="selectpicker form-control" name="office" v-validate="'required'" v-model="editBusiness.office">
                                     <option value="Yes" :selected="(rowData.office == 'Yes') ? 'selected' : ''">Yes</option>
                                     <option value="No" :selected="(rowData.office == 'No') ? 'selected' : ''">No</option>
                                  </select>
                                  <span class="customError">{{ errors.first('office') }}</span>
                               </div>

                         </div>

                         <div class="form-group">
                    <dropzone ref="companyEditDropzone" id="dropzone" :options="dropzoneOptions"></dropzone>
                        <a v-show="dbImages.length > 0" href="javascript:void(0)" class="pull-right" @click="showremoveableImages()">Show Docs</a>
                         </div>


                      </fieldset>
                   </form>
                </td>
                <td colspan="1">
                   <form class="well form-horizontal">
                      <fieldset>

                          <div class="form-group" :class="{'has-error': errors.has('group') }">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Group</span>
                                  <select multiple="true" class="selectpicker form-control" name="group" v-model="editBusiness.group">
                                  <template v-for="g in groups">
                                   <option v-validate="'required'" :selected="(newFilter( rowData.groups ).includes( g.title )) ? 'selected' : ''">{{g.title}}</option>
                                  </template>
                                    

                                  </select>
                                  <span class="customError">{{ errors.first('group') }}</span>
                               </div>
                               <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                                 
                                    Selected : <template v-for="gD in rowData.groups">
                                      {{gD.groups+', '}}
                                    </template>
                                      <br>
                                    New Selected : {{editBusiness.group}}
                               </div>

                         </div>

                         <div class="form-group" :class="{'has-error': errors.has('gst') }">
                               <div class="input-group"><span class="input-group-addon">Gst</span><input v-validate="'min:17|max:17'" v-mask="'##-##-####-###-##'" name="gst" placeholder="Gst" class="form-control" type="text" v-model="editBusiness.gst"></div>
                               <span class="customError">{{ errors.first('gst') }}</span>

                         </div>

                         <div class="form-group" :class="{'has-error': errors.has('product') }">
                               <div class="input-group"><span class="input-group-addon">Product</span><input name="product" placeholder="Product" class="form-control" type="text" v-model="editBusiness.product"></div>
                               <span class="customError">{{ errors.first('product') }}</span>

                         </div>

                         <div class="form-group" :class="{'has-error': errors.has('category') }">

                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Category</span>
                                  <select class="selectpicker form-control" name="category" v-model="editBusiness.category">
                                     <option value="Individual" :selected="(rowData.category == 'Individual') ? 'selected' : ''">Individual</option>
                                     <option value="Company" :selected="(rowData.category == 'Company') ? 'selected' : ''">Company</option>
                                     <option value="Firm" :selected="(rowData.category == 'Firm') ? 'selected' : ''">Firm</option>
                                  </select>
                                  <span class="customError">{{ errors.first('category') }}</span>
                               </div>

                         </div>


                        <template v-if="editBusiness.category === 'Firm' || editBusiness.category === 'Company'">


                             <div class="form-group" v-if="editBusiness.category == 'Firm'" :class="{'has-error': errors.has('criteria') }">
                               <div class="input-group">
                                  <span class="input-group-addon" style="max-width: 100%;">Criteria</span>

                                  <select class="selectpicker form-control" name="criteria" v-model="editBusiness.criteria">
                                     <option value="Limited" :selected="(rowData.criteria == 'Limited') ? 'selected' : ''">Limited</option>
                                     <option value="SMC Limited" :selected="(rowData.criteria == 'SMC Limited') ? 'selected' : ''">SMC Limited</option>
                                     <option value="Private Limited" :selected="(rowData.criteria == 'Private Limited') ? 'selected' : ''">Private Limited</option>
                                     <option value="Public Limited" :selected="(rowData.criteria == 'Public Limited') ? 'selected' : ''">Public Limited</option>
                                  </select>
                                  <span class="customError">{{ errors.first('criteria') }}</span>

                               </div>

                         </div>

                         <div class="form-group" :class="{'has-error': errors.has('aop') }">

                               <div class="input-group"><span class="input-group-addon">Aop</span><input v-mask="'#######-#'" v-validate="'required'" name="aop" placeholder="aop" class="form-control" type="text" v-model="editBusiness.aop"></div>
                               <span class="customError">{{ errors.first('aop') }}</span>

                         </div>

                         </template>



                          <div class="form-group">

                            <div class="btn-group pull-right">
                              <a class="btn btn-default" role="button" @click="closeEditDialoge()">Cancel</a>
                              <a class="btn btn-primary" role="button" @click="submitEditData()">Save</a>
                            </div>

                         </div>



                      </fieldset>


                   </form>
                </td>
                                </tr>
                            </tbody>

                        </table>
                        </div>
            <div v-show="removeableImages" class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
              <template>
                
              <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3" v-for="i in dbImages">
                <img :src="i.docs" width="100%" height="200">
                <button class="mb-xs mt-xs mr-xs btn btn-danger btn-block" @click="removeEachDoc(i.id)">Remove</button>
              </div>
            
              </template>
            </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


    
  </div>

  </div>
</template>

<script>
import { Validator } from 'vee-validate'
import axios from 'axios'
import moment from 'moment'
import {mask} from 'vue-the-mask'
import 'viewerjs/dist/viewer.css'
import vue2Dropzone from 'vue2-dropzone'
import Viewer from "v-viewer/src/component.vue"
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/css/vue-popper.css';


export default {
  components: {dropzone: vue2Dropzone, Viewer, Popper},
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
        submitIndex: 0,
        addIndex: 0,
        groups:{},
        message: '',
        removeableImages: false,
        email: '',
        designation: '',
        editBusiness:{
          name: this.rowData.name,
          address: this.rowData.address,
          office: this.rowData.office,
          website: this.rowData.website,
          group: [],
          gst: this.rowData.gst,
          product: this.rowData.product,
          category: this.rowData.category,
          criteria: this.rowData.criteria,
          aop: this.rowData.aop,
        },
        dropzoneOptions: {
          url: '/',
          thumbnailWidth: 150,
          autoProcessQueue: false,
          acceptedFiles: 'image/*',
          addRemoveLinks: true,
          maxFilesize: 3,
          maxFiles: 3,
          dictDefaultMessage: '<p><i class="fa fa-caret-right text-primary"></i> Drop Documents Here</p>',
        },
        partners: [],
        toggle1: false,
        toggle1Loading: false,
        toggle2: false,
        toggle2Loading: false,
        toggle3: false,
        toggle3Loading: false,
        images: [],
        dbImages: [],
        dpImages: [],
        showGallery: false,
    }
  },
  watch: {
    showGallery: function (val) {

            if (val) {

                this.images = []
                axios.get('/businessmen/fetchhirarcy/'+this.rowData.id)
               .then(res => {

                      for (var i = 0; i < res.data.length; i++) {
                          this.images.push(res.data[i].docs)
                      }

                      this.showGallery = true

               })
            }
    },
  },
  computed:{
    //     filterGroups(){
    //     var sG = []
    //     for (var i = 0; i < this.rowData.groups.length; i++) {
    //           sG.push(this.rowData.groups[i].groups)
    //     }

    //     return this.editBusiness.group = sG
    // },
  },
  methods: {
    AddNewBusinessEmail(scope, id, bid){
        this.$validator.validateAll(scope).then((result) => {

            if (result) {

                        this.addIndex = id

                            axios.post('/business/email/add',{

                                'params' : {
                                      'business_id'     : bid,
                                      'businessmen_id'  : id,
                                      'email'           : this.email,
                                }

                            }).then(res => {

                              this.addIndex = 0
                              this.email = ''

                            axios.get('/business/partners/'+this.rowData.id)
                            .then(res =>{
                              this.partners = ''
                              this.partners = res.data
                            })

                                      this.$notify({
                                      group: 'success',
                                      type: 'success',
                                      duration: 4000,
                                      title: 'Success',
                                      text: 'Business Email Added',
                                      })
                              

                            }).catch(err => {
                              this.addIndex = 0

                                      this.$notify({
                                      group: 'success',
                                      type: 'error',
                                      duration: 4000,
                                      title: 'Error',
                                      text: err.response.data.error.email[0],
                                      })
                            })

            }

        })
    },
    submitBusinessEmail(scope, id){
        this.$validator.validateAll(scope).then((result) => {

            if (result) {
                        this.submitIndex = id

                  if (this.email == '') {
                        this.submitIndex = 0
                        this.message = 'Please provide valid email address'
                        return false
                    }else{

                            axios.post('/business/email/'+id+'/'+this.email)
                             .then(res => {
                              this.submitIndex = 0
                              this.email = ''

                                  if (res.data == 1) {

                                    axios.get('/business/partners/'+this.rowData.id)
                                    .then(res =>{
                                      this.partners = ''
                                      this.partners = res.data
                                    })

                                      this.$notify({
                                      group: 'success',
                                      type: 'success',
                                      duration: 4000,
                                      title: 'Success',
                                      text: 'Business Email Updated',
                                      })

                                  }
                              


                            }).catch(err => {
                              this.submitIndex = 0
                              this.message = err.response.data.error.email[0]
                            })

                    }
            }

        })
    },
     clean(obj) {
        let newObject = obj
        for (var propName in newObject) { 
          if (newObject[propName] === '' || newObject[propName] === undefined || newObject[propName] === null) {
            delete newObject[propName]
          }
        }

        return newObject
      },

    editDesignation(scope, businessmen_id){
      this.$validator.validateAll(scope).then((result) => {

          if (result) {
            this.submitIndex = 'designation'

            if (this.designation == '') {
              this.message = 'Please provide valid designation'
              return false
            }else{
              axios.post('/business/editDesignation', {
                  params:{
                      'businessmen_id' : businessmen_id,
                      'business_id' : this.rowData.id,
                      'designation' : this.designation
                  }
              }).then(res => {

                this.submitIndex = 0
                this.designation = ''
                this.message = ''

                // Reset Partner Data
                axios.get('/business/partners/'+this.rowData.id)
                .then(res =>{
                  this.partners = ''
                  this.partners = res.data
                })

                this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'New Designation assigned',
                })

              }).catch( err => {
                     this.submitIndex = 0
                    this.message = err.response.data.error[0]               
              })
            }



          }

        })

    },
    submitEditData(){
      this.$validator.validateAll().then((result) => {

          if (result) {

              var fileInstance = this.$refs.companyEditDropzone.dropzone


              if (fileInstance.getQueuedFiles() != '') {

                this.dpImages = []
                for (var i = 0; i < fileInstance.getAcceptedFiles().length; i++) {
                  this.dpImages.push(fileInstance.getAcceptedFiles()[i].dataURL)
                }

                this.editBusiness.docs = []
                Vue.set( this.editBusiness, 'docs', this.dpImages )
                    
              }

                      let refreshedObject = this.clean(this.editBusiness)

                        axios.post('/business/edit/'+this.rowData.id, {'params' : refreshedObject})
                        .then( res => {

                        this.toggle3 = false
                        fileInstance.removeAllFiles()
                        this.$events.fire('filter-set' ,15)
                        this.$notify({
                          group: 'success',
                          type: 'success',
                          duration: 4000,
                          title: 'Success',
                          text: 'Business Updated',
                          })
                        }).catch( err =>{

                          this.$notify({
                            group: 'success',
                            type: 'error',
                            duration: 4000,
                            title: 'Error',
                            text: err.response.data.error,
                            })   

                        })

          }

      })
    },
        newFilter(data){
        var sG = []
        for (var i = 0; i < data.length; i++) {
              sG.push(data[i].groups)
        }

        return sG    
        },
    removeDoc(data){
        axios.delete('/business/hirarcy?image='+data)
              .then(res => {console.log(res)})
    },
    dateConverter(date){
      return moment(date).fromNow()
    },
    toggler(data){
      if (data === 'toggle1') {
          /*
              fetch realted partners
          */

              if (!this.toggle1) {

                  this.toggle1Loading = true

                  axios.get('/business/partners/'+this.rowData.id)
                  .then(res =>{
                    this.partners = res.data
                    this.toggle1 = true
                    this.toggle2 = false
                    this.toggle3 = false
                    this.toggle1Loading = false
                  })
              }


      }else if(data === 'toggle2'){
              if (!this.toggle2) {

                  this.toggle2Loading = true
                  this.images = []
                  axios.get('/business/hirarcy/'+this.rowData.id)
                  .then(res =>{

                      for (var i = 0; i < res.data.length; i++) {
                          this.images.push(res.data[i])
                      }

                    this.toggle2 = true
                    this.toggle1 = false
                    this.toggle3 = false
                    this.toggle2Loading = false
                  })

              } 
      }else{
              if (!this.toggle3) {

                  this.toggle3Loading = true


                  let fetchedDataEdit = this.fetchDataEdit()

                  fetchedDataEdit.then(res => {

                    this.groups = []
                    this.groups = res.groupFetch.data
                    this.dbImages = []
                    this.dbImages = res.businessImageFetch.data

                    this.toggle3Loading = false

                  })

                  this.toggle3 = true
                  this.toggle1 = false
                  this.toggle2 = false

              }            
      }
    },

      async fetchDataEdit(){

           let groupFetch = await axios.get('/businessmen/groups')
           let businessImageFetch = await axios.get('/business/docs/'+this.rowData.id)

           return { groupFetch, businessImageFetch }
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

    closeEditDialoge(){
      this.toggle3 = false
    },
    removeEachDoc(id){
            swal({
              title: "Are you sure?",
              text: "This action cannot be undone.",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
                    axios.delete('/business/docs/'+id)
                    .then(res => {


                        /*
                            Refresh Docs Div
                        */


                    this.toggle3Loading = true
                  let fetchedDataEdit = this.fetchDataEdit()

                  fetchedDataEdit.then(res => {
                    this.dbImages = res.businessImageFetch.data

                    this.toggle3Loading = false

                  })

                        /*
                            Refresh Docs Div End
                        */


                          this.$notify({
                          group: 'success',
                          type: 'success',
                          duration: 4000,
                          title: 'Success',
                          text: 'Document Removed',
                          })

                    })         
          }}) 
    },
    onClick (event) {
      // console.log('my-detail-row: on-click', event.target)
    },
    addDocument(){

    },
    showremoveableImages(){
      (this.removeableImages) ? this.removeableImages = false : this.removeableImages = true
    },
  },
  mounted(){

          var sG = []
          for (var i = 0; i < this.rowData.groups.length; i++) {
                sG.push(this.rowData.groups[i].groups)
          }

           this.editBusiness.group = sG


          // var eG = []
          // for (var i = 0; i < this.rowData.emails.length; i++) {
          //       eG.push(this.rowData.emails[i].email)
          // }

          //  this.editBusiness.emails = eG

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
  height:100px;
}
.display{
  display: block;
}
.nDisplay{
  display: none;
}
.nopadding button{
  border-radius: 0px;
}
</style>