<template>
<div>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>{{title}}</h2>
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="#">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span>Layouts</span></li>
                    <li><span>Light Sidebar</span></li>
                </ol>
                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
            </div>
        </header>
        <div class="row">
            <div class="col-md-12">
                <!-- <stockChart :options="stockOptions"></stockChart> -->
                <highcharts :options="stockOptions" ref="highcharts"></highcharts>
            </div>
            
        </div>
    </section>
</div>
</template>
<script>
// import Chart from 'highcharts-vue/demo/src/components/Chart'
// import StockChart from 'highcharts-vue/demo/src/components/StockChart'
import moment from 'moment'
export default {
name: 'Dashboard',
    // components: {
    // //     chart: Chart,
    //     stockChart: StockChart,
    // },
    computed:{
    title(){
        return 'Dashboard'
    },
    },
    data(){
    return {
      stockOptions: {
        rangeSelector: {
          verticalAlign: 'top',
          x: 0,
          y: 0,
          floating: true,
          y: 250 
        },
        title: {
          text: 'Income & Expense'
        },
      subtitle: {
        text: 'Pakistan Businessmen Association per day graph',
      },
    navigation: {
        menuStyle: {
            background: '#E0E0E0'
        }
    },
    xAxis: {
        type: 'datetime',
           labels: {
            formatter: function(){
              return moment(new Date(this.value)).format('MM-DD-YYYY')
            },
        },
    },
      yAxis: {
        title: {
          text: 'Rupees (Pkr)'
        },
    },
    tooltip: {
        valueDecimals: 0,
        valueSuffix: ' Pkr',
        shared: true
    },
    legend: {
      borderWidth: 0
    },
    series: []
    }
    }
    },
    mounted(){
                    axios.get('/ledger/graph').then(res => {

                    this.$refs.highcharts.options.series = [
                        {
                          name: 'Income',
                          showInNavigator: true,
                          marker: {
                          enabled: false
                          },
                          lineWidth: 1,
                          shadow: false,
                          type: 'spline',
                          pointStart: Date.UTC(res.data[2].dateYear, res.data[3].dateMonth, res.data[4].dateDay),
                          pointInterval: 24 * 3600 * 1000,
                          pointEnd: new Date(),
                          data: res.data[0].income,
                        },
                        {
                          name: 'Expense',
                          showInNavigator: true,
                          marker: {
                          enabled: false
                          },
                          shadow: false,
                          lineWidth: 1,
                          type: 'spline',
                          pointStart: Date.UTC(res.data[2].dateYear, res.data[3].dateMonth, res.data[4].dateDay),
                          pointInterval: 24 * 3600 * 1000,
                          pointEnd: new Date(),
                          data: res.data[1].expense,
                        }
                    ]

                 })

    },
}
</script>
<style>
.highcharts-credits{
  display: none;
}
</style>