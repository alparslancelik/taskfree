<template>
  <div class="how-it-works hero is-light">
    <h4 class="title is-4 has-text-centered">How It Works</h4>

    <div class="columns">
      <div class="column">
        <h5 class="title is-5 how-title">Submit a Job</h5>
        <div class="content">
          Submit a job in three simple steps.
          <ol>
            <li>Pick a category or create your own</li>
            <li>Set manpowers needed for the job</li>
            <li>Set starting time, end time and reward</li>
          </ol>
        </div>
      </div>

      <div class="column">
        <h5 class="title is-5 how-title">Wait for Helpers</h5>
        <div class="content">
          It may take several days for handymans to notice and
          sign up to your job.
          <br/><br/>
          Once the number of manpower is fullfilled, the handymen will begin
          their work.
        </div>
      </div>

      <div class="column">
        <h5 class="title is-5 how-title is-dark">Complete the Job</h5>
        <div class="content">
          Don't forget to give the rewards to the handymen.
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {

}
</script>

<style lang='scss'>

.how-it-works {
  color: gray !important;
  padding: 20px;

  .content {
    height: 200px;
  }

  .how-title {
    color: black !important;
    background-color: white;
    padding: 10px;
  }

  .content {
    padding: 10px;
    background-color: white;
  }
}

</style>
