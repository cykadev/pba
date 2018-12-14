 <template>
 <div>
   
    <div class="custom-actions">
      
      <template v-if="rowData.assigned[0]">
        
      <button class="btn btn-sm alert-success" @click="itemAssigned()"><i class="glyphicon glyphicon-user"></i></button>

      </template>

      <template v-else>
        
      <button class="btn btn-sm" @click="itemAction('assign-item', rowData, rowIndex)"><i class="glyphicon glyphicon-user"></i></button>

      </template>
      <button class="btn btn-sm" @click="itemAction('view-item', rowData, rowIndex)"><i class="glyphicon glyphicon-eye-open"></i></button>
      <button class="btn btn-sm" @click="itemAction('change-status', rowData, rowIndex)"><i class="glyphicon glyphicon-refresh" :class="{'glyphicon-refresh-animate' : activeIndex === rowIndex}"></i></button>
      <button class="btn btn-sm" @click="itemAction('delete-item', rowData, rowIndex)"><i class="glyphicon glyphicon-trash alert-danger"></i></button>
    </div>
 </div>
  </template>

  <script>
    import assigntobusinessmen from '../../../modals/addBusinessmen'
  export default {
    components:{assigntobusinessmen : assigntobusinessmen},
    data() {
      return {
        activeIndex: undefined
      }
    },
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    computed:{
      clickedStatus(){
        return this.clickedStatus = this.$store.state.status
      }
    },
    methods: {

      itemAssigned(){

                this.$notify({
                  group: 'success',
                  type: 'error',
                  duration: 4000,
                  title: '<i class="fa fa-times"></i> Error',
                  text: 'Invoice already assigned to '+ this.rowData.assigned[0]['br_number'],
                })

      },

      itemAction (action, data, index) {
      if (action == 'change-status'){

              this.activeIndex = index

              axios.put('/invoice/'+data.id)
              .then(res => setTimeout(() => {

                this.$notify({
                  group: 'success',
                  type: 'success',
                  duration: 4000,
                  title: '<i class="fa fa-check"></i> Success',
                  text: 'Receipt updated and logged in Cashbook',
                })

                  this.activeIndex = undefined,
                  this.$parent.refresh()
              }, 2000)).catch( err =>{

                  this.activeIndex = undefined,
                  this.$notify({
                    group: 'success',
                    type: 'error',
                    duration: 4000,
                    title: '<i class="fa fa-times"></i> Error',
                    text: err.response.data,
                  })
              })

              // axios.put('/invoice/'+data.id)
              // .then(res => setTimeout(() => {this.activeIndex = undefined, this.$parent.refresh()}, 2000))


        }else if(action === 'view-item'){

                axios.get('/invoice/'+data.id)
                        .then(res => this.$store.dispatch('initviewprint', res.data))

                    
        }else if(action === 'assign-item'){


            if (data.invoice_status == 1) {

              this.$modal.show(assigntobusinessmen, {
                id: data.id
              }, {
                draggable: true,
                scrollable: true,
                height: 'auto'
              })

            }else{

                 this.$notify({
                  group: 'success',
                  type: 'error',
                  duration: 4000,
                  title: '<i class="fa fa-times"></i> Error',
                  text: 'Log the Cashbook before assigning to Businessmen',
                })

            }
                    
        }
        else{

            swal({
              title: "Are you sure?",
              text: "This action can only be proceedable when it's not logged in Cashbook, please check carefully before proceeding.",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
              axios.delete('/invoice/'+data.id)
              .then(res => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: '<i class="fa fa-check"></i> Success',
                text: 'Charges removed',
              })
            }
                ).catch(err => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'error',
                duration: 4000,
                title: '<i class="fa fa-times"></i> Error',
                text: err.response.data,
              })
            }

                )          
              }

            })

        }
      },
      editcharges(){
      }

    }
  }
  </script>

  <style>
    .custom-actions button.ui.button {
      padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
      margin: auto !important;
    }
    .glyphicon-refresh-animate {
        color: #0088cc;
        -animation: spin 2s infinite linear;
        -webkit-animation: spin2 2s infinite linear;
    }

    @-webkit-keyframes spin2 {
        from { -webkit-transform: rotate(0deg);}
        to { -webkit-transform: rotate(360deg);}
    }

    @keyframes spin {
        from { transform: scale(1) rotate(0deg);}
        to { transform: scale(1) rotate(360deg);}
    }
    div.swal-text{
      text-align: justify;
    }
    .v--modal-overlay {
    position: fixed;
    box-sizing: border-box;
    left: 0;
    top: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1021;
    opacity: 1;
    }

  </style>