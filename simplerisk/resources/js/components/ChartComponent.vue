<template>
    <div>
        <bubble-chart></bubble-chart>
        <line-chart></line-chart>
    </div>
</template>

<script>
    import 'chart.js'
    import BubbleChart from './BubbleChart'
    import LineChart from './LineChart'
    export default {
        name: 'ChartComponent',
        components:  {
            BubbleChart,
            LineChart
        }
    }
</script>
