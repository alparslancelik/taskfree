<template>
  <button @click="pickTask"
    class="button is-medium is-primary" :disabled="!canPick">{{label}}</button>
</template>

<script>
export default {
  props: ['tid'],
  data () {
    return {
      label: 'Loading',
      task: null,
      picks: []
    }
  },
  computed: {
    isLoggedIn () {
      return this.$api.currentUser.isLoggedIn
    },
    label () {
      if (this.picks && this.task) {
        const naccepted = this.picks.filter((n) => n.accepted).length
        if (this.picks.find((p) => p.uid === this.$api.currentUser.uid)) {
          return 'Waiting'
        }
        if (naccepted >= this.task) {
          return 'Full'
        }
        return 'Pick'
      } else {
        return 'Loading...'
      }
    },
    canPick () {
      if (this.picks && this.task) {
        const naccepted = this.picks.filter((n) => n.accepted).length
        return (naccepted < this.task.npeople) &&
          !(this.picks.find((p) => p.uid === this.$api.currentUser.uid)) &&
          this.task.creator !== this.$api.currentUser.uid
      } else {
        return false
      }
    }
  },
  async mounted () {
    this.refresh()
  },
  methods: {
    async refresh () {
      this.task = await this.$api.getTask(this.tid)
      this.picks = await this.$api.getPickByTaskId(this.tid)
    },
    async pickTask () {
      await this.$api.pickTask(this.$route.params.id)
      await this.refresh()
      this.$emit('task-picked')
    }
  }
}
</script>

<style lang='scss'>
  .step {
    margin-bottom: 10px !important;
  }
</style>
