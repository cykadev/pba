<template>
  <div>
    <business-filter-bar></business-filter-bar>
    <!-- http://vuetable.ratiw.net/api/users -->
    <business ref="business"
      api-url="/business"
      :fields="fields"
      pagination-path=""
      :css="css.table"
      :multi-sort="true"
      detail-row-component="business-detail-row"
      :append-params="moreParams"
      @vuetable:cell-clicked="onCellClicked"
      @vuetable:pagination-data="onPaginationData"
    ></business>
    <div class="vuetable-pagination">
      <vuetable-pagination-info ref="paginationInfobusiness"
        info-class="pagination-info"
      ></vuetable-pagination-info>
      <vuetable-pagination ref="paginationbusiness"
        :css="css.pagination"
        @vuetable-pagination:change-page="onChangePage"
      ></vuetable-pagination>
    </div>
  </div>
</template>

<script>
import accounting from 'accounting'
import moment from 'moment'
import business from 'vuetable-2'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import VueEvents from 'vue-events'
import CustomActions from './actions/CustomAction'
import DetailRow from './actions/DetailRow'
import FilterBar from './actions/FilterBar'
Vue.use(VueEvents)
Vue.component('business-custom-actions', CustomActions)
Vue.component('business-detail-row', DetailRow)
Vue.component('business-filter-bar', FilterBar)

        // {
        //   name: '__checkbox',
        //   titleClass: 'text-center',
        //   dataClass: 'text-center',
        // },
export default {
  components: {
    business,
    VuetablePagination,
    VuetablePaginationInfo,
  },
  data () {
    return {
      categories: [],
      fields: [
        {
          name: '__sequence',
          title: '#',
          titleClass: 'text-right',
          dataClass: 'text-right'
        },
        {
          name: 'br_number',
          title: 'Registration',
          sortField: 'br_number',
          titleClass: 'text-center',
          dataClass: 'text-center'
        },
        {
          name: 'name',
          sortField: 'name',
          titleClass: 'text-center',
          dataClass: 'text-center',
        },
        {
          name: 'groups',
          sortField: 'groups',
          title: 'Group',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'customGroup'
        },
        {
          name: 'category',
          sortField: 'category',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'customCnic'
        },
        {
          name: 'status',
          sortField: 'status',
          titleClass: 'text-center',
          dataClass: 'text-center',
          callback: 'status'
        },
        {
          name: '__component:business-custom-actions',
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
      moreParams: {}
    }
  },
  methods: {
    customGroup(data){
        var sG = ''
        for (var i = 0; i < data.length; i++) {

          if(i%2 == 0){
              sG += `<span class="label label-primary">${data[i].groups}</span><br>`
          }else{
              sG += `<span class="label label-default">${data[i].groups}</span><br>`
            
          }

        }

        return sG
    },
    customCnic (value) {
              if (value == '') {
                    return '<i class="fa fa-times alert-danger"></i>'
              }else{
                return value
              }
          },
    allcap (value) {
      return value.toUpperCase()
    },
    status (value) {

      if (value == 1) {
        return '<span class="label label-success"> Valid</span>'
      }else{
        return '<span class="label label-danger"> Expired</span>'
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
      this.$refs.paginationbusiness.setPaginationData(paginationData)
      this.$refs.paginationInfobusiness.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.business.changePage(page)
    },
    onCellClicked (data, field, event) {
      this.$refs.business.toggleDetailRow(data.id)
    },
     refreshTable (filterText) {
      this.moreParams = {
        filter: filterText
      }
      Vue.nextTick( () => this.$refs.business.refresh() )
    },
  },
  events: {
     'refresh-table' () {

      this.moreParams = {
         per_page: 15,
      }
      Vue.nextTick( () => this.$refs.business.refresh() )
    },
    'filter-set' (pagination) {
      this.moreParams = ''
      this.moreParams = {
        ini: true,
        per_page: pagination,
      }
      Vue.nextTick( () => this.$refs.business.refresh() )
    },
    'search-set' (query, pagination, category) {
      this.moreParams = ''
      this.moreParams = {
        q: query,
        per_page: pagination,
        category:category,
      }
      Vue.nextTick( () => this.$refs.business.refresh() )
    },
    'filter-reset' () {
      this.moreParams = ''
      this.moreParams = {
        per_page: 15
      }
      Vue.nextTick( () => this.$refs.business.refresh() )
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