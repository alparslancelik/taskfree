<template>
  <div v-show="done" class="task-list">
    <h1 v-show="category" class="title">{{category.name}}</h1>
    <div v-for="task in tasks">
      <task-card :tid="task.tid" :showCover="!cid"></task-card>
    </div>
  </div>
</template>

<script>
  import TaskCard from './TaskCard'

  export default {
    components: {
      TaskCard
    },
    data () {
      return {
        done: false,
        category: null,
        tasks: []
      }
    },
    async created () {
      this.cid = this.$route.query['category']
      const tasks = await this.$api.listTask(parseInt(this.cid, 10))
      this.tasks = tasks.filter((t) => !t.completed)
      if (this.cid) {
        this.category = await this.$api.getCategory(this.cid)
      } else {
        this.category = {
          name: 'All Categories'
        }
      }
      this.done = true
    }
  }
</script>

<style lang='scss'>
  .task-list {
    margin-top: 2em;
    h1 {
      text-align: center;
    }
  }
  .create-task {
    margin-top: 20px;
  }
</style>
