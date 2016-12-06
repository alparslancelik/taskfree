<template>
  <div v-show='task && creator' class="view-task">
    <h1 class="title is-2">{{task.title}}</h1>
    <h2 class="subtitle is-4">{{creator.name}}</h2>
    <button @click="completeTask" v-show="canComplete"
        :disabled="task.completed" class="button is-primary is-medium">
      {{ completionButtonLabel }}
    </button>
    <pick-status :tid="task.tid"></pick-status>
    <button @click="deleteTask" class="button is-danger is-medium">
      Delete
    </button>
    <router-link :to="'/task/' + task.tid + '/edit'" class="button is-warning is-medium">
      Edit
    </router-link>
    <div class="content">
      <div class="columns">
        <article class="message column is-6">
          <div class="message-header">Description</div>
          <div class="message-body">{{task.description || 'No description'}}</div>
        </article>
        <article class="message column is-6">
          <div class="message-header">Location</div>
          <div class="message-body">{{task.description || 'No description'}}</div>
        </article>
      </div>
      <article v-show="steps" class="message">
        <div class="message-header">Steps</div>
        <div class="message-body">
          <step v-for="(step, idx) in steps"
                v-bind:index="idx" :isEditable="false" v-bind:step="step">
          </step>
        </div>
      </article>
      <article class="message is-bold">
        <div class="message-header">Workers (required = {{task.npeople}})</div>
        <div class="message-body">
          <pick-list v-show="picks" :picks="picks"></pick-list>
        </div>
      </article>
    </div>
  </div>
</template>

<script>
  import PickList from './PickList'
  import PickStatus from './PickStatus'
  import Step from './Step'

  export default {
    components: {
      Step,
      PickStatus,
      PickList
    },
    data () {
      return { task: null, creator: null, steps: [], picks: [] }
    },
    methods: {
      async completeTask () {
        await this.$api.completeTask(this.$route.params.id)
      },
      async pickTask () {
        await this.$api.pickTask(this.$route.params.id)
      },
      async deleteTask () {
        await this.$api.deleteTask(this.$route.params.id)
        this.$router.push('/task')
      },
      async refresh () {
        this.task = await this.$api.getTask(this.$route.params.id)
        this.steps = await this.$api.getSteps(this.$route.params.id)
        this.picks = await this.$api.getPickByTaskId(this.$route.params.id)
        console.log(this.picks)
        if (this.task.creator) {
          this.creator = await this.$api.getUser(this.task.creator)
        } else {
          this.creator = {
            uid: 0,
            name: 'Unknown Creator'
          }
        }
      }
    },
    computed: {
      completionButtonLabel () {
        return this.task && this.task.completed ? 'Completed' : 'Mark as Complete'
      },
      canComplete () {
        return this.task && this.creator &&
          this.$api.currentUser &&
          (this.task.creator === this.$api.currentUser.uid)
      },
      canPick () {
        return this.task && this.creator &&
          this.$api.currentUser &&
          (this.task.creator !== this.$api.currentUser.uid)
      }
    },
    async created () {
      this.$on('task-picked', () => {
        this.refresh()
      })
      this.refresh()
    }
  }
</script>

<style lang='scss'>
  .view-task {
    padding: 20px;
    h1, .subtitle {
      text-align: center;
    }
    .content {
      margin-top: 10px;
    }
  }
</style>
