<template>
  <div>
    <pettycash-filter-bar></pettycash-filter-bar>
    <!-- http://vuetable.ratiw.net/api/users -->
    <pettycash ref="pettycash"
      api-url="/pettycash"
      :fields="fields"
      :render-icon='renderIcon'
      pagination-path=""
      :css="css.table"
      :multi-sort="true"
      detail-row-component="pettycash-detail-row"
      :append-params="moreParams"
      @vuetable:cell-clicked="onCellClicked"
      @vuetable:pagination-data="onPaginationData"
    ></pettycash>
    <div class="vuetable-pagination">
      <vuetable-pagination-info ref="paginationInfopettycash"
        info-class="pagination-info"
      ></vuetable-pagination-info>
      <vuetable-pagination ref="paginationpettycash"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
      ></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from 'accounting'
import moment from 'moment'
import pettycash from 'vuetable-2'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'
import CustomActions from './actions/CustomAction'
import DetailRow from './actions/DetailRow'
import FilterBar from './actions/FilterBar'
Vue.use(VueEvents)
Vue.component('pettycash-custom-actions', CustomActions)
Vue.component('pettycash-detail-row', DetailRow)
Vue.component('pettycash-filter-bar', FilterBar)

        // {
        //   name: '__checkbox',
        //   titleClass: 'text-center',
        //   dataClass: 'text-center',
        // },
export default {
  components: {
    pettycash,
    VuetablePagination,
    VuetablePaginationInfo,
  },
  data () {
    return {
      fields: [
        {
          name: '__sequence',
          title: '#',
          titleClass: 'text-right',
          dataClass: 'text-right'
        },
        {
          name: 'date',
          sortField: 'date',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'category',
          sortField: 'category',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },  
        {
          name: 'total',
          sortField: 'total',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: '__component:pettycash-custom-actions',
          title: 'Manage',
          titleClass: 'text-center',
          dataClass: 'text-center'
        }
      ],
      css: {
        table: {
          tableClass: 'table table-bordered table-striped table-hover',
          ascendingIcon: 'glyphicon glyphicon-chevron-up',
          descendingIcon: 'glyphicon glyphicon-chevron-down'
        },
        pagination: {
          wrapperClass: 'pagination',
          activeClass: 'active',
          disabledClass: 'disabled',
          pageClass: 'page',
          linkClass: 'link',
          icons: {
            first: '',
            prev: '',
            next: '',
            last: '',
          },
        },
        icons: {
          first: 'glyphicon glyphicon-step-backward',
          prev: 'glyphicon glyphicon-chevron-left',
          next: 'glyphicon glyphicon-chevron-right',
          last: 'glyphicon glyphicon-step-forward',
        },
      },
      moreParams: {
        'filter': ''
      }
    }
  },
  methods: {
    renderIcon (classes, options) {
      // return `<i class="${classes.join('fa fa-spin')}" ${options}></i>`
      return `fa fa-spin`
    },
    allcap (value) {
      return value.toUpperCase()
    },
    status (value) {

      if (value == 1) {
        return '<span class="label label-success"> Paid</span>'
      }else{
        return '<span class="label label-danger"> Pending</span>'
      }
      
    },
    formatNumber (value) {
      return accounting.formatNumber(value, 2)
    },
    formatDate (value, fmt = 'D MMM YYYY') {
      return (value == null)
        ? ''
        : moment(value, 'YYYY-MM-DD').format(fmt)
    },
    onPaginationData (paginationData) {
      this.$refs.paginationpettycash.setPaginationData(paginationData)
      this.$refs.paginationInfopettycash.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.pettycash.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.pettycash.toggleDetailRow(data.id)
    },
  },
  events: {
     'refresh-table' () {

      var fmt = 'YYYY-MM-DD'
      var initialDate = ''
      initialDate = moment(new Date(), 'YYYY-MM-DD').format(fmt) + '_' + moment(new Date(), 'YYYY-MM-DD').format(fmt)

      this.moreParams = {
         filter: initialDate,
         per_page: 15,
         category:{}
      }
      Vue.nextTick( () => this.$refs.pettycash.refresh() )
    },
    'filter-set' (filterText, category, pagination) {
      this.moreParams = {
        filter: filterText,
        per_page: pagination,
        category:{category},
      }
      Vue.nextTick( () => this.$refs.pettycash.refresh() )
    },
    'search-set' (query, pagination) {
      this.moreParams = {
        filter:'',
        q: query,
        per_page: pagination,
        category:{},
      }
      Vue.nextTick( () => this.$refs.pettycash.refresh() )
    },
    'filter-reset' () {
      this.moreParams = {
        per_page: 15
      }
      Vue.nextTick( () => this.$refs.pettycash.refresh() )
    }
  }
}
</script>
<style>
.pagination {
  margin: 0;
  float: right;
}
.pagination a.page {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.page.active {
  color: white;
  background-color: #337ab7;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 10px;
  margin-right: 2px;
}
.pagination a.btn-nav {
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
}
.pagination a.btn-nav.disabled {
  color: lightgray;
  border: 1px solid lightgray;
  border-radius: 3px;
  padding: 5px 7px;
  margin-right: 2px;
  cursor: not-allowed;
}
.pagination-info {
  float: left;
}
</style>