<template>
  <article v-if="creator && task" class="task columns">
    <figure class="column is-1 is-mobile">
      <div class="columns pick-number">
        <div :class="bgClass" class="column is-6 number-picked">{{numberOfPick}}</div>
        <div class="column is-6 number-total">{{task.npeople}}</div>
      </div>
    </figure>
    <div class="column is-11 is-mobile">
      <div class="content">
        <p>
          <router-link :to="'/task/' + task.tid">
            <strong>{{task.title}}</strong>
          </router-link>
          <br/>
          <small>{{creator.name}}</small>
          <br/>
          {{task.description}}
        </p>
      </div>
    </div>
  </article>
</template>

<script>
  export default {
    props: ['tid', 'showCover'],
    data () {
      return {
        task: null,
        creator: null,
        numberOfPick: 0,
        pickStatus: 'Pick'
      }
    },
    methods: {
      async pickTask () {
        await this.$api.pickTask(this.tid)
        await this.refresh()
      },
      async refresh () {
        this.task = await this.$api.getTask(this.tid)
        this.creator = await this.$api.getUser(this.task.creator)
        const picks = await this.$api.getPickByTaskId(this.tid)
        const status = picks.find((p) => p.uid === this.$api.currentUser.uid)
        if (status && !status.accepted) {
          this.pickStatus = 'Awaiting Confirmation'
        } else if (status) {
          this.pickStatus = 'Accepted'
        }
        this.numberOfPick = picks.filter((p) => p.accepted).length
      }
    },
    computed: {
      bgClass () {
        return {
          'bg-dark': true,
          'bg-warning': this.pickStatus === 'Awaiting Confirmation',
          'bg-success': this.pickStatus === 'Accepted'
        }
      },
      pickClass () {
        return {
          'is-disabled': this.pickStatus !== 'Pick',
          'is-warning': this.pickStatus === 'Awaiting Confirmation',
          'is-success': this.pickStatus === 'Accepted'
        }
      }
    },
    async mounted () {
      this.refresh()
    }
  }

</script>

<style lang='scss'>
  @import '~bulma';

  .task {
    padding: 0px 10px;

    .pick-number {
      font-weight: bold;
      background: white;
    }

    .content {
      background: white;
      padding: 20px;
    }

    .bg-dark {
      background: $grey-dark;
    }

    .bg-warning {
      background: $warning;
    }

    .pick-number {
      margin-top: 10px;
    }

    .bg-primary {
      background: $success;
    }

    .number-picked {
      color: white;
      font-size: 1.2em;
    }

    .number-total {
      color: black;
    }
    .content {
      height: 120px;
    }
  }
</style>
