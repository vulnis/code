<script>
  import { Bubble } from 'vue-chartjs'
  export default { 
    extends: Bubble,
    data () {
      return {
        dataCollection: [],
        options: {
            scales: {
                
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Probability'
                    },
                    ticks: {
                        display: false,
                        beginAtZero:true,
                        min: 0,
                        max: 5
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Severity'
                    },
                    ticks: {
                        display: false,
                        beginAtZero:true,
                        min: 0,
                        max: 5
                    }
                }]
            }
        }
      }
    },
    created() {
      axios.get(`assessments`)
      .then(response => {
        // loop through the response data and create x,y,r structures.
        const data = [];
        response.data.forEach(function(value) {
            data.push({
                x: value.probability.value,
                y: value.severity.value,
                r: 10
            })
        });
        const collection = {
            label: 'Assessments',
            backgroundColor: '#f87979',
            data: data
        }
        this.dataCollection.push(collection);
        this.renderChart({datasets: this.dataCollection}, this.options)
      })
      .catch(e => {
        this.errors.push(e)
      })
    },
    mounted () {
        //
    }
  }
</script>
