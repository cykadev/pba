<template>
	<div style="height:1300px;">
  <div class="row">
    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3">
      <h3>Select</h3>
    </div>
    <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
    <h3>Preview</h3>
    </div>
  </div>

<div class="row">
  <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3">

      <p>Select Business</p>

      <div class="list-group" v-show="business != ''">

        <template v-for="b in business">
          
      <div class="radio list-group-item">
        <label><input type="radio" v-model="selectedBusiness" name="business" :value="b">{{b.name}}</label>
      </div>
        </template>

    </div>

    <p>Select Print Document</p>

<div class="list-group">
  <a href="#" class="list-group-item list-group-item-action" @click="activate(1, 'card_front')" :class="(index == 1) ? 'active' : ''">ID Card Front</a>
  <a href="#" class="list-group-item list-group-item-action" @click="activate(2, 'card_back')" :class="(index == 2) ? 'active' : ''">ID Card Back</a>
  <a href="#" class="list-group-item list-group-item-action" @click="activate(3, 'br_certificate')" :class="(index == 3) ? 'active' : ''">Business Certificate</a>
  <a href="#" class="list-group-item list-group-item-action" @click="activate(4, 'bmr_certificate')" :class="(index == 4) ? 'active' : ''">Businessmen Certificate</a>
  <a href="#" class="list-group-item list-group-item-action" @click="activate(5, 'mr_certificate')" :class="(index == 5) ? 'active' : ''">Membership Certificate</a>
  <a href="#" style="color: #ffffff;" class="label-danger list-group-item list-group-item-action" v-if="erroExist">{{errorMsg}}</a>
</div>

<div class="btn-group btn-group-justified">

      <a class="btn btn-default" role="button" @click="$emit('close')">Cancel</a>
      <a class="btn btn-default" role="button" @click="printSelectedDoc()">Print</a>
</div>
  </div>
  <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8">

        <!-- ID Card Front -->
        <div v-if="index === 1" id="printDiv" :class="srcImage" style="width:325px;height:204px;"><br><br>
        <div class="row cardHeadingWhite">

          <div class="col-sm-12 text-center">
          <div class="pull-left">
            <span>Card #123</span>
          </div>
            <span>{{params.membership}} Class</span>

          </div>

        <!-- <div class="col-sm-8">
          </div> -->

        </div>

        <div class="row" style="margin-top:5px;">

          <div class="col-sm-3 alignImageCard">
          <img :src="params.image" alt="" width="62" height="76">
          </div>

          <div class="col-sm-9 text-center">
          <p style="color:blue;margin-bottom:5px;line-height:11px;" v-if="params.membership != 'Executive'">Associate Class</p><p style="color:blue;margin-bottom:5px;line-height:11px;" v-else>Corporate Class</p>
          <p style="font-size:14px;line-height:12px;margin-bottom:3px;">{{params.name}}</p>
          <p style="line-height:8px;font-size:9px">{{selectedBusiness.designation}}</p>
          <p style="font-size:14px;line-height:12px;margin-bottom:2px;">{{selectedBusiness.name}}</p>
          <p style="color:red;font-size:10px;margin-bottom:0px;">Valid upto: {{expiryDate}}</p>
          </div>

        </div>

        <div class="row">
        <div class="col-sm-12" style="margin-left:13px">

          <p v-if="params.membership == 'Executive'" style="font-size:10px;line-height:10px;margin-bottom:1px;">{{params.executive_class}} Businessmen</p>
          <p style="font-size:10px;line-height:10px;margin-bottom:1px;"><span style="color:#084023;">Membership: </span>{{params.bmr_number}}</p>
          <p style="font-size:10px;line-height:10px;margin-bottom:1px;"><span style="color:#084023;">Business Reg: </span>{{selectedBusiness.br_number}}</p>
          
        </div>
        </div>


        </div>

        <!-- ID Card Back -->
        <div v-if="index === 2" id="printDiv" :class="srcImage" style="width:325px;height:204px;">
              
            <!-- check Gst -->
            <template v-if="selectedBusiness.gst">

                  <div class="row">
                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-top:5px;margin-right:19px;margin-bottom:0px;">{{selectedBusiness.gst}}</span>

                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:42px;margin-top:-2px;" v-if="params.cnic">{{params.cnic}}</span>

                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:42px;margin-top:-2px;" v-else>{{params.overseas_cnic}}</span>

                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:121px;margin-top:-2px;">{{params.ntn}}</span>

                  </div>

                  <div class="row" style="height: 27px;">
                    <div class="col-sm-2"><span>Address:</span></div>
                    <div class="text-center col-sm-10"><p style="line-height: 11px;margin-top: 6px;margin-bottom: 0px;">{{params.address}}</p></div>
                  </div>

                  <div class="row" style="margin-top: 0px;">
                    <div class="col-sm-12 text-center">

                      <span v-if="params.phone_number" style="color: #ffffff;font-size: 15px;">Tel No. {{params.phone_number}} </span><span v-if="params.phone_number" style="color: #ffffff;font-size: 15px;">Cell No. {{params.mobile_number}}</span>
                    </div>
                  </div>

            </template>

            <template v-else>
                <div class="row">
                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:43px;padding-top: 19px" v-if="params.cnic">{{params.cnic}}</span>

                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:43px;padding-top: 19px" v-else>{{params.overseas_cnic}}</span>

                  <span class="pull-right" style="font-size:16px;color:#ffffff;letter-spacing:5px;margin-right:121px;margin-top:-2px;">{{params.ntn}}</span>

                  </div>

                  <div class="row" style="height: 25px;margin-top: 9px;">
                    <div class="col-sm-2"><span>Address:</span></div>
                    <div class="text-center col-sm-10"><p style="line-height: 11px;margin-bottom: 0px;">{{params.address}}</p></div>
                  </div>

                  <div class="row" style="margin-top: 0px;">
                    <div class="col-sm-12 text-center">

                      <span v-if="params.phone_number" style="color: #ffffff;font-size: 15px;">Tel No. {{params.phone_number}} </span><span v-if="params.phone_number" style="color: #ffffff;font-size: 15px;">Cell No. {{params.mobile_number}}</span>
                    </div>
                  </div>             
            </template>



        </div>

        <!-- br_certificate -->
        <div v-if="index === 3" id="printDiv" :class="srcImage" style="width: 816px;height: 767px;">
              <div class="row" style="width: 816px;padding-top: 384px;">
                <div class="row" style="margin-bottom: 9px;">
                  
                <div class="col-sm-offset-2 col-sm-7 text-center">
                      <p style="font-size: 18px;font-weight: bold;">
                  <template v-for="t in selectedBusiness.types">
                      {{ t.type}}
                  </template>
                    </p>
                </div>
                <div class="col-sm-3"><p style="font-size: 18px;margin-left: 10px;font-weight: bold;">{{selectedBusiness.br_number}}</p></div>

                </div>

                <div class="row">
                  <div class="col-sm-offset-4 col-sm-8">
                      <p style="font-size: 18px;font-weight: bold;">
                        {{selectedBusiness.name}}
                      </p>

                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-offset-1 col-sm-11 text-center">
                      <p style="font-size: 14px;font-weight: bold;">
                        {{selectedBusiness.address}}
                      </p>

                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-offset-2 col-sm-10">
                      <p style="font-size: 18px;font-weight: bold;margin-top: -5px;">
                        {{params.name}} <span style="font-size: 12px;font-weight: normal;">({{selectedBusiness.designation}})</span>
                      </p>

                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-offset-4 col-sm-8">
                      <template v-if="params.membership == 'Executive'">     
                      <p style="margin-left: 20px;">
                        Under the Category "<b style="font-size: 18px;">{{params.executive_class}} Businessmen</b>"
                      </p>
                      </template>
                      <template v-else>     
                      <p style="margin-left: 20px;">
                        Under the Category "<b style="font-size: 18px;">{{params.membership}} Businessmen</b>"
                      </p>
                      </template>

                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-offset-8 col-sm-4">
                      <p style="font-size: 16px;font-weight: bold;color: maroon;margin-left: 36px;margin-top: 11px;">
                          {{expiryDate}}
                      </p>

                  </div>
                </div>
              

            </div>
        </div>
  
        <!-- bmr_certificate -->
        <div v-if="index === 4" id="printDiv" :class="srcImage" style="width: 806px;height: 758px;"><br><br>
          <div class="row" style="margin-top: 65px;">

            <div class="row">
              <div class="col-sm-offset-8 col-sm-4">
                <p style="font-size: 18px;margin-left:22px;font-weight: bold;">{{params.bmr_number}}</p>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-offset-8 col-sm-4"><p style="font-size: 18px;margin-left:22px;font-weight: bold;"><!-- GR-{{selectedBusiness.groups[0].id}}-{{selectedBusiness.groups[0].code}} -->&nbsp;</p></div>
            </div>

            <div class="row" style="margin-top: 205px;">
              <div class="col-sm-offset-3 col-sm-9"><p style="font-size: 18px;margin-left:22px;font-weight: bold;">{{params.name}} <span style="font-size: 12px;font-weight: normal;">({{selectedBusiness.designation}})</span></p></div>
            </div>

            <div class="row">
              <div class="col-sm-8">
                <p style="font-size: 18px;margin-left:22px;font-weight: bold;margin-left: 200px;">{{params.cnic}}</p>
              </div>
              <div class="col-sm-4">
                <p style="font-size: 18px;margin-left:22px;font-weight: bold;">{{params.ntn}}</p>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-offset-3 col-sm-9"><p style="font-size: 18px;margin-left:22px;margin-top: -5px;font-weight: bold;">{{selectedBusiness.name}}</p></div>
            </div>

            <div class="row">
              <div class="col-sm-12 text-center"><p style="font-size: 14px;margin-top: -5px;margin-left: 105px;margin-right:105px;line-height:10px;font-weight: bold;">{{selectedBusiness.address}}</p></div>
            </div>

            <div class="row">
              <div class="col-sm-offset-1 col-sm-11 text-center"><p style="font-size: 18px;margin-top: 5px;font-weight: bold;">{{selectedBusiness.groups[0].groups}}</p></div>
            </div>

            <div class="row"><br><br>
              <div class="col-sm-offset-8 col-sm-4">
                  <p style="font-size: 16px;font-weight: bold;color: maroon;margin-left: 10px;margin-top: 11px;">
                      {{expiryDate}}
                  </p>

              </div>
            </div>

          </div>
        </div>

        <!-- mr_certificate -->
        <div v-if="index === 5" id="printDiv" :class="srcImage" style="width: 825px;height: 768px;">

            <div class="row">
                <div class="row" style="margin-top: 65px;">
                    <div class="col-sm-12 text-center">
                      <h3>{{params.membership}}</h3>
                    </div>
                </div>


                <div class="row" style="margin-top: 28px;">
                    <div class="col-sm-12 text-center">
                      <p style="line-height:11px;text-align:center;margin:0 0 0px;font-size:16px;">Pakistan Businessmen Association</p>
                      <p style="line-height:11px;text-align:center;margin:0 0 0px;font-size:11px;">D-5 4th Floor, Osif Center Plot B-25, Block 13-A,</p>
                      <p style="line-height:11px;text-align:center;margin:0 0 0px;font-size:11px;">University Road, Gulshan-e-Iqbal, Karachi-Pakistan.</p>
                      <p style="line-height:11px;text-align:center;margin:0 0 0px;font-size:11px;">Tel: +92-21-34975773, 348221881 Fax: +92-21-34821882</p>
                      <p style="line-height:11px;text-align:center;margin:0 0 4px;font-size:11px;">Email: <a>info@apba.org.pk</a>, <a>www.apba.org.pk</a></p>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px;">
                    <div class="col-sm-12 text-center">
                      <p style="font-size: 12px;">(Under Section-42 of the Companies Ordinance, 1948 XL VII of 1948)</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-7 text-center">
                        <p style="margin-left: 140px;font-size: 14px;font-weight: bold;" v-if="params.membership == 'Executive'">Executive Class</p>
                        <p style="margin-left: 140px;font-size: 14px;font-weight: bold;" v-else>Associate Class</p>
                    </div>
                    <div class="col-sm-5 text-center">
                        <p style="font-size: 14px;font-weight: bold;margin-bottom: 0px;">{{params.bmr_number}}</p>
                        <p style="font-size: 14px;font-weight: bold;margin-right: 30%;">Business Reg. No. {{selectedBusiness.br_number}}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                      <p style="font-size: 14px;font-weight: bold;margin-left: 230px;margin-top: -5px;">{{selectedBusiness.name}}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                      <p style="font-size: 14px;font-weight: bold;margin-left: 120px;margin-top: 27px;">{{selectedBusiness.address}}</p>
                    </div>
                </div>

                <div class="row" style="margin-top: 13px;">
                    <div class="col-sm-6">
                        <p style="font-size: 14px;font-weight: bold;margin-left: 200px;">{{selectedBusiness.gst}}</p>
                      </div>
                    <div class="col-sm-6">
                        <p style="font-size: 14px;font-weight: bold;margin-left: 100px;">{{params.ntn}}</p>
                        <p style="font-size: 14px;font-weight: bold;margin-left: 6px;margin-top: -13px;">CNIC No. {{params.cnic}}</p>

                    </div>
                </div>

                <div class="row" style="margin-top: 29px;">
                    <div class="col-sm-offset-8 col-sm-4">
                  <p style="font-size: 14px;font-weight: bold;color: maroon;margin-left: 10px;">
                      {{expiryDate}}
                  </p>
                    </div>
                </div>

                <div class="row" style="margin-top: 65px;">
                    <div class="col-sm-6 text-center">
                      <img src="/certificates/pba/pbalogo.png" style="margin-top: -16px;margin-left: 16;width: 138px;">
                    </div>
                    <div class="col-sm-6">
                  <p style="font-size: 14px;font-weight: bold;color: maroon;margin-left: 10px;">
                      <span style="margin-left: 65px;">{{explodedExpiryDate[1]}}</span> <span style="margin-left: 100px;">{{explodedExpiryDate[0]}}</span> <span style="margin-left: 50px;">{{explodedExpiryDate[2]}}</span>
                  </p>
                    </div>
                </div>

            </div>
        </div>
      
  </div>
</div>




	</div>

</template>
<script>
// import pdf from 'vue-pdf'
import printJS from 'print-js'
import moment from 'moment'
export default{
    props: {
      params: {
        type: Object,
        required: true
      }
    },
	name: 'printdocs',
    // components: {pdf},
    data() {
      return {
        business: '',
        print: '',
        erroExist: false,
        selectedBusiness : '',
        src: '',
        errorMsg: '',
        index: null,
      	loader: false,
      }
    },
    computed:{
        srcImage(value){
          return this.src
        },
        expiryDate(){
          return moment().add(1, 'Y').format("MMM Do YYYY"); 
        },
        memberexpiryFormat(){
          return moment().add(1, 'Y').format("MMM Do YY"); 
        },
        explodedExpiryDate(){
          return this.memberexpiryFormat.split(' ')
        }
    },
    methods:{
      printSelectedDoc(){
        if (this.selectedBusiness == '' || this.index == null) {
            this.errorMsg = 'Please select document to Print.'
            this.erroExist = true
        }else{
            this.erroExist = false
            this.errorMsg = ''
            this.printDiv()
        }
      },

          printDiv(){

              printJS({
                  printable: 'printDiv',
                  type:'html',
                  targetStyles: ['*']
              })

          },
    //   printDiv(){
    //     var printContents = document.getElementById('printDiv').innerHTML
    //     var originalContents = document.body.innerHTML
    //     document.body.innerHTML = printContents
    //       window.print()

    //     document.body.innerHTML = originalContents
    // },
      activate(id, certificate){


        if (certificate === 'card_front') {
            this.src = certificate
            this.index = id

        }else if (certificate === 'card_back') {

                // Check for gst to change image
            (this.selectedBusiness.gst) ? this.src = 'card_back_gst' : this.src = certificate



            this.index = id

        }else if (certificate === 'br_certificate') {
            this.src = certificate
            this.index = id


        }else if (certificate === 'bmr_certificate') {
            this.src = certificate
            this.index = id


        }else {
            this.src = certificate
            this.index = id


        }



        // if (id === this.index) {
        //     this.index = null
        //     return
        // }else{
        //   this.loader = true
        //   this.src = certificate
        //   this.index = id
        //   axios.get('/businessmen/certificate/'+this.params.id+'/'+certificate)
        //         .then(res =>{
        //             this.src = res.data
        //         }).catch(err =>{
        //             console.log(err)
        //         })
        // }


      }

    },
    mounted(){
                axios.get('/businessmen/business/'+this.params.id)
                .then(res => {
                  this.business = res.data
                })
    },
}
	
</script>
<style>
.input-group .form-control {
    position: relative;
    z-index: 0;
    float: left;
    width: 100%;
    margin-bottom: 0;
}

.load-bar {
  position: relative;
  margin-top: 20px;
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #da4733;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #3b78e7;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 50%; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
.list-group li a{
  color: #fff;
}
.v--modal-overlay {
    background: #fff;
    z-index: 1021;
    opacity: 1;
    }

.customBorder{
  border: 2px solid #777;
}

.card_front{
  width:325px;
  height:204px;
  background-image: url('/certificates/pba/card_front.jpg');
  background-repeat: no-repeat;
  background-size: contain;
}

.card_back{
  width:325px;
  height:204px;
  background-image: url('/certificates/pba/card_back.jpg');
  background-repeat: no-repeat;
  background-size: contain;
}

.card_back_gst{
  width:325px;
  height:204px;
  background-image: url('/certificates/pba/card_back_gst.jpg');
  background-repeat: no-repeat;
  background-size: contain;
}

.br_certificate{
  width: 816px;
  height:767px;
  background-image: url('/certificates/pba/br_certificate.jpg');
  background-repeat: no-repeat;
  background-size: contain;
}

.bmr_certificate{
  width: 806px;
  height: 758px;
  background-image: url('/certificates/pba/bmr_certificate.jpg');
  background-repeat: no-repeat;
  background-size: contain;
}

.mr_certificate{
  width: 825px;
  height: 768px; 
  background-image: url('/certificates/pba/mr_certificate.jpg');
  background-repeat: no-repeat;
  background-size: contain; 
}


/* 

    ID Card CSS

 */

.cardHeadingWhite{
  color: #ffffff;
}
.cardHeadingWhite span:first-of-type{
    margin-left: 0px;
}
.alignImageCard img{
  margin-left: 14px;
}
.customLineHeight p {
  line-height: 11px;
}


</style>