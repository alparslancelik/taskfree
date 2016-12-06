<template>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Rating</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="pickUser in pickUsers">
        <td>{{ pickUser.user.name }}</td>
        <td>
          <button :disabled='pickUser.pick.accepted'
                  v-show="canAcceptMore" class="button">
            {{ pickUser.pick.accepted ? 'Accepted' : 'Accept' }}
          </button>
        </td>
        <td>{{ pickUser.pick.rating }}</td>
        <td>{{ pickUser.pick.comment }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
  export default {
    props: ['picks'],
    mounted () {
      this.picks.forEach(async (p) => {
        this.pickUsers.push({
          task: await this.$api.getTask(p.tid),
          user: await this.$api.getUser(p.uid),
          pick: p
        })
      })
    },
    computed: {
      canAcceptMore () {
        return this.pickUsers && this.pickUsers[0] && this.pickUsers[0].task &&
            this.pickUsers.filter(pu => pu.pick.accepted).length < this.pickUsers[0].task.npeople
      }
    },
    data () {
      return {
        pickUsers: []
      }
    }
  }
</script>

<style lang='scss'>
</style>
