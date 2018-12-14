 <template>
    <div class="custom-actions">
    <modal name="history">
      <h3>History</h3>
    </modal>
    <modal name="partners">
      <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 text-center">
        <h2>Add New Partner</h2>

      </div>
    </modal>
    <!-- <button class="btn btn-sm alert-default" @click="itemAction('record-item', rowData, rowIndex)"><i class="glyphicon glyphicon-th-list"></i></button> -->
      <!-- <button :class="(rowData.status) ? 'btn btn-sm alert-success' : 'btn btn-sm alert-danger'" @click="itemAction('status-item', rowData, rowIndex)"><i :class="(rowData.status) ? 'glyphicon glyphicon-menu-up' : 'glyphicon glyphicon-menu-down'"></i></button> -->

        <button class="btn btn-sm alert-default" @click="itemAction('partner-item', rowData, rowIndex)" :key="rowIndex"><i class="glyphicon glyphicon-user"></i></button>

    </div>
  </template>

  <script>
  import partners from '../../../../modals/addPartners'
  export default {
    components:{partners : partners},
    data() {
      return {
        selectedBusinessmen: '',
        options: [],
        isLoading: false,
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

    asyncFind(query){

        if (query.length > 2) {

          this.isLoading = true
        axios.get('/businessmen/asyncsearch?q='+query)
           .then(res => {
            this.options = res.data
            this.isLoading = false
           })

        }

    },

      itemAction (action, data, index) {

        if (action == 'status-item') {

            swal({
              title: "Are you sure?",
              text: "Update Status for "+this.rowData.bmr_number,
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

          }else if(action == 'partner-item'){

            // this.$modal.show('partners')
              this.$modal.show(partners, {
                id: data.id
              }, {
                draggable: true,
                scrollable: true,
                height: 'auto',
              })
          }
          else{
              this.$modal.show('history')
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
    background: rgba(0, 0, 0, 0.6);
    z-index: 1021;
    opacity: 1;
    }
  </style>