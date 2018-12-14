 <template>
    <div class="custom-actions">
    <button v-show="checkExpiry(rowData)" class="btn btn-sm" @click="itemAction('renew-item', rowData, rowIndex)"><i class="glyphicon glyphicon-warning-sign alert-danger"></i></button>
    <button class="btn btn-sm alert-default" @click="itemAction('record-item', rowData, rowIndex)"><i class="glyphicon glyphicon-th-list"></i></button>
    <button class="btn btn-sm alert-default" @click="itemAction('print-item', rowData, rowIndex)"><i class="glyphicon glyphicon-print"></i></button>
      <button :class="(rowData.status) ? 'btn btn-sm alert-success' : 'btn btn-sm alert-danger'" @click="itemAction('status-item', rowData, rowIndex)"><i :class="(rowData.status) ? 'glyphicon glyphicon-arrow-up' : 'glyphicon glyphicon-arrow-down'"></i></button>

    </div>
  </template>

  <script>
  import moment from 'moment'
  import renewal from '../../../../modals/renewal'
  import history from '../../../../modals/history'
  import printDocs from '../../../../modals/printDocs'
  export default {
    data() {
      return {
        duration: 0,
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
      checkExpiry(data){

        if (this.rowData.renewal != null) {

            var now = moment(new Date()) //todays date
            var end = moment(this.rowData.renewal.expiry_date) // another date
            var duration = moment.duration(now.diff(end))
            var months = duration.asMonths()

          
            if (Math.floor(months) >= -1 ) {

                    if (Math.floor(months) >= 0) {
                        this.duration = Math.floor(months)
                    }

                return true

            }else{
                return false
            }


        }

      },

      itemAction (action, data, index) {

        if (action == 'status-item') {

            swal({
              title: "Are you sure?",
              text: "Update status for "+this.rowData.bmr_number+" changes take effect on primary website",
              icon: "warning",
              buttons: ["Back", "Confirm"],
              dangerMode: true,
            }).then((done) => {

              if (done) {
              axios.put('/businessmen/status/'+data.id)
              .then(res => {

              this.$parent.refresh()
              this.$notify({
                group: 'success',
                type: 'success',
                duration: 4000,
                title: 'Success',
                text: 'Status Updated for '+this.rowData.bmr_number,
              })
            }).catch(err => {

              this.$notify({
                group: 'success',
                type: 'error',
                duration: 4000,
                title: 'Error',
                text: 'Error! something went wrong unable to update status for '+this.rowData.bmr_number,
              })
            })          
          }}) 

          }else if(action == 'renew-item'){

              swal({
                title: "Membership Expiry",
                text: `Membership for the ${this.rowData.bmr_number} businessmen expires soon`,
                icon: "warning",
                buttons: ["Back", "Renew"],
                dangerMode: true,
              }).then((done) => {

                if (done) {
                      /*Open Renewal Dialoge*/
                      this.$modal.show(renewal, {
                        id: data.id,
                        duration: this.duration,
                        expiry: this.rowData.renewal.expiry_date
                      }, {
                        draggable: false,
                        scrollable: true,
                        width: '60%',
                        height: 'auto',
                      })
              }})   

          }else if(action == 'print-item'){

                      this.$modal.show(printDocs, {
                        params: this.rowData,
                      }, {
                        scrollable: true,
                        width: '100%',
                        height: 'auto',
                      })

          }else{
                      this.$modal.show(history, {
                        id: data.id,
                      }, {
                        draggable: false,
                        scrollable: true,
                        width: '60%',
                        height: 'auto',
                      })
          } 

      },


    },
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
    .v--modal-overlay {
    position: fixed;
    box-sizing: border-box;
    left: 0;
    top: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0, 0, 0, 0.2);
    z-index: 1021;
    opacity: .9;
    }
  </style>