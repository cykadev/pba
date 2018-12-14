<template>
    <div class="filter-bar">
      <form class="form-inline">
        <div class="form-group">
          <label>Search for:</label>
          <input type="text" v-model="query" class="form-control" @keyup.enter="doFilter" placeholder="Title">
          <button class="btn" @click.prevent="doFilter">Go</button>
          <button class="btn" @click.prevent="resetFilter">Reset</button>         
        </div>

    <div class="btn-group" :class="(active) ? 'open' : ''" @click="opencategory()">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle" data-toggle="dropdown" :aria-expanded="(active) ? 'true' : 'false'">Category <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">

      <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="category" value="Petty Cash"> Petty Cash</label></a></li>
      <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="category" value="Membership"> Membership</label></a></li>
      <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="category" value="Donation"> Donation</label></a></li>

      <template v-for="v in categories">
          <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="category" :value="v.category"> {{v.category}}</label></a></li>
      </template>
      </ul>
    </div>

    <div class="btn-group" :class="(active2) ? 'open' : ''" @click="opentype()">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle" data-toggle="dropdown" :aria-expanded="(active2) ? 'true' : 'false'">Type <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
          <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="type" value="Income"> Income</label></a></li>
     <li><a tabindex="0"><label class="checkbox"><input type="checkbox" v-model="type" value="Expense"> Expense</label></a></li>
      </ul>
    </div>


          <date-picker v-model="date" date-format="YYYY-MM-DD" type="date" confirm lang="en" range :shortcuts="shortcuts"></date-picker>

      <div class="btn-group pull-right">
        <downloadExcel
        class   = "mb-xs mt-xs mr-xs btn btn-default dropdown-toggle"
        :fetch  = "fetchData"
        :fields = "json_fields"
        title   = "Cash Book"
        default-value = "-"
        :footer  = "footerXls"
        name    = "Cashbook.xls">
        <i class="fa fa-file-excel-o" style="color:green;"></i>
        </downloadExcel>
    </button>
    </div>


    <div class="btn-group pull-right" :class="(active3) ? 'open' : ''" @click="openpagination()">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default dropdown-toggle" data-toggle="dropdown" :aria-expanded="(active3) ? 'true' : 'false'">Per Page <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">

        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="15"> 15 Perpage</label></a></li>
        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="50"> 50 perpage</label></a></li>
        <li><a tabindex="0"><label class="radio"><input type="radio" v-model="paginationValue" value="100"> 100 perpage</label></a></li>

      </ul>
    </div>

    <div class="btn-group pull-right">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-default" disabled>Selected: {{paginationValue}}</button>
    </div>


      </form>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import moment from 'moment'
  export default {
    components: { DatePicker },
  data() {
    return {

       json_fields: {
            'Date': 'date',
            'Type': 'type',
            'Category': 'category',
            'Payer' : 'payer',
            'Payment Method' : 'method',
            'Ref#' : 'ref',
            'Description': 'description',
            'Total': 'total'
        },
        json_meta: [
            [
                {
                    'key': 'charset',
                    'value': 'utf-8'
                }
            ]
        ],

      paginationValue: 15,
      query: '',
      category: [],
      type: [],
      filterText:'',
      active: false,
      active2: false,
      active3: false,
      date: '',
      xlsDate: '',
      shortcuts: [
        {
          text: 'Today',
          onClick: () => {
            this.date = [ new Date(), new Date() ]
          }
        },
      ],
      timePickerOptions:{
        start: '00:00',
        step: '00:30',
        end: '23:30'
      }
    }
  },
  computed:{
      categories(){
        return this.$store.state.cashbookcategories
      }
  },
  watch: {
    // whenever question changes, this function will run
    date: function (val, oldval) {

        var fmt = 'YYYY-MM-DD'
        var dat = moment(val[0], 'YYYY-MM-DD').format(fmt)
        var dat2 = moment(val[1], 'YYYY-MM-DD').format(fmt)

        this.xlsDate = dat + '_' + dat2

      setTimeout( () => {this.$events.fire('filter-set', dat + '_' + dat2, this.category, this.type, this.paginationValue)},500)

    }
  },
  mounted(){

    console.log({"category":this.category})

    var fmt = 'YYYY-MM-DD'
    var initialDate = ''
    initialDate = moment(new Date(), 'YYYY-MM-DD').format(fmt) + '_' + moment(new Date(), 'YYYY-MM-DD').format(fmt)

    this.xlsDate = initialDate

    this.$events.fire('filter-set', initialDate, this.category, this.type, this.paginationValue)

  },
    methods: {
      footerXls(){
        return 'This File Generated On '+ new Date()
      },
      async fetchData(){

        const response = await axios.get('/cashbook/xls?sort=&page=1&per_page='+this.paginationValue+'&filter='+this.xlsDate+'&category='+[this.category]+'&type='+[this.type])

        return response.data.data

      },
      opencategory(){
        (this.active == true) ? this.active = false : this.active = true
      },
      opentype(){
        (this.active2 == true) ? this.active2 = false : this.active2 = true
      },
      openpagination(){
        (this.active3 == true) ? this.active3 = false : this.active3 = true
      },
      doFilter () {
        this.$events.fire('search-set',this.query , this.paginationValue)
      },
      UpdateSelected () {
        // this.$events.fire('filter-set', this.filterText)
      },
      RemoveSelected () {
        // this.$events.fire('filter-set', this.filterText)
        console.log('remove_all')
      },
      resetFilter () {
          var fmt = 'YYYY-MM-DD'
          var initialDate = ''
          initialDate = moment(new Date(), 'YYYY-MM-DD').format(fmt) + '_' + moment(new Date(), 'YYYY-MM-DD').format(fmt)

          this.xlsDate = initialDate

          this.$events.fire('refresh-table', initialDate)
      }
    }
  }
</script>
<style scoped>
.filter-bar {
  padding: 10px;
}
.margin{
  margin-left: 1%;
}
.form-horizontal .checkbox {
    min-height: 27px;
}
.dropdown-menu{
    font-size: 14px;
    list-style: none;
}
/*.mx-datepicker-range {
  width: 61%;
}*/
</style>