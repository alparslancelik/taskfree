<template>
  <div class="create-task">
    <div class="columns">
      <div class="column is-8">
        <h1 class="title is-2">{{mode}} Task</h1>
      </div>
    </div>

    <h2 class="title is-4">Details</h2>
    <div class="box">

      <div class="columns">
        <div class="column is-5">
          <h2 class="title is-5">Title</h2>
          <div v-show="submitRequested && !isTitleValid"
               class="message is-danger">Please provide a title</div>
        </div>
        <div class="column is-6">
          <input v-model="title"
                 class="input" type="text" placeholder="Give your task a name" />
        </div>
      </div>

      <div class="columns">
        <div class="control column is-5">
          <h2 class="title is-5">Location</h2>
        </div>
        <div class="control column is-6">
          <button class="button is-warning">
            <span class="icon">
              <i class="fa fa-map" />
            </span>
            &nbsp; No Location Provided
          </button>
        </div>
      </div>

      <div class="columns">
        <div class="column is-5">
          <h2 class="title is-5">Description</h2>
        </div>
        <div class="column is-6">
          <textarea v-model="description"
            tr="5" class="textarea is-fullwidth" type="text">
          </textarea>
        </div>
      </div>

      <div class="columns">
        <div class="column is-5">
          <h2 class="title is-5">Start Time</h2>
          <div
            v-show="submitRequested && !isStartValid"
            class="message is-danger">
          Must be later than now</div>
        </div>
        <div class="column is-7">
          <date-picker :date="startTime" :option="startOption">
          </date-picker>
        </div>
      </div>

      <div class="columns">
        <div class="column is-5">
          <h2 class="title is-5">End Time</h2>
          <div v-show="submitRequested && !isEndValid"
            class="message is-danger">Must be later than start time</div>
        </button>
      </div>
      <div class="column is-7">
        <date-picker :date="endTime" :option="startOption">
        </date-picker>
      </div>
    </div>

    <div class="columns">
      <div class="control column is-5">
        <h2 class="title is-5">Manpower Needed</h2>
      </div>
      <div class="control column is-6">
        <button @click="increaseManpower" class="button is-white">
          <span class="icon">
            <i class="fa fa-plus-square-o" />
          </span>
        </button>
        <button class="button">{{manpower}}</button>
        <button @click="decreaseManpower" class="button is-white">
          <span class="icon">
            <i class="fa fa-minus-square-o" />
          </span>
        </button>
      </div>
    </div>

    <div class="column">
      <button @click="createTask" class="button is-success is-medium">
        Submit
      </button>
      <button @click="deleteTask" class="button is-danger is-medium">
        Delete
      </button>
    </div>
  </div>



  <h2 class="title is-4">
    Steps
  </h2>

  <div v-for="(step, index) in steps">
    <step :index="index" :step="step" :isEditable="true"></step>
  </div>

  <button v-show="mode === 'Create'"
          @click="addStep" class="button is-primary">Add More</button>
</div>
</template>

<script>
  import moment from 'moment'
  import DatePicker from 'vue-datepicker'
  import Step from './Step'

  export default {
    components: {
      Step,
      DatePicker
    },
    async created () {
      if (this.$route.name === 'edit-task') {
        const task = await this.$api.getTask(this.$route.params['id'])
        this.title = task.title
        this.latitude = task.latitude
        this.longitude = task.longitude
        this.description = task.description
        this.startTime.time = task.start_time
        this.endTime.time = task.end_time
        this.manpower = task.npeople
        this.steps = await this.$api.getSteps(task.tid)
      }
    },
    data () {
      return {
        submitRequested: false,
        title: '',
        latitude: 0,
        longitude: 0,
        description: '',
        startTime: {
          time: ''
        },
        endTime: {
          time: ''
        },
        startOption: {
          type: 'min',
          week: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          month: [
            'January', 'February', 'March', 'April', 'May', 'June', 'July',
            'August', 'September', 'October', 'November', 'December'
          ],
          format: 'YYYY-MM-DD hh:mm',
          placeholder: 'Click to Change',
          inputStyle: {
            'display': 'inline-block',
            'padding': '6px',
            'line-height': '22px',
            'font-size': '16px',
            'border': '2px solid #fff',
            'box-shadow': '0 1px 3px 0 rgba(0, 0, 0, 0.2)',
            'border-radius': '2px',
            'color': '#5F5F5F'
          },
          color: {
            header: '#ccc',
            headerText: '#f00'
          },
          buttons: {
            ok: 'Ok',
            cancel: 'Cancel'
          },
          overlayOpacity: 0.5, // 0.5 as default
          dismissible: true // as true as default
        },
        category: 1,
        manpower: 1,
        steps: []
      }
    },
    computed: {
      mode () {
        if (this.$route.name === 'create-task') {
          return 'Create'
        } else {
          return 'Edit'
        }
      },
      startLimit () {
        return [
          {
            type: 'from',
            from: '2016-02-01'
          }
        ]
      },
      endLimit () {
        return [
          {
            type: 'from',
            from: '2016-03-01'
          }
        ]
      },
      isTitleValid () {
        return this.title.length >= 1
      },
      isStartValid () {
        return this.mode === 'Edit' || moment(this.startTime.time) > moment()
      },
      isEndValid () {
        return !this.endTime.time || (moment(this.endTime.time) > moment(this.startTime.time))
      },
      newTask () {
        return {
          title: this.title,
          start_time: this.startTime.time,
          end_time: this.endTime.time,
          description: this.description,
          longitude: 0,
          latitude: 0,
          npeople: this.manpower,
          cid: this.category,
          steps: this.steps
        }
      }
    },
    methods: {
      async createTask () {
        this.submitRequested = true
        try {
          if (this.mode === 'Create') {
            const newTask = await this.$api.createTask(this.newTask)
            this.$router.push(`/task/${newTask.tid}`)
          } else {
            await this.$api.updateTask(
              this.$route.params['id'],
              this.newTask
            )
            this.$router.push(`/task/${this.$route.params['id']}`)
          }
        } catch (e) {
        }
      },
      async deleteTask () {
        await this.$api.deleteTask(this.$route.params['id'])
      },
      increaseManpower () {
        this.manpower++
      },
      decreaseManpower () {
        if (this.manpower >= 2) {
          this.manpower--
        }
      },
      async deleteStep (idx) {
        if (this.steps[idx] && this.steps[idx].sid) {
          this.$api.deleteStep(this.steps[idx].sid)
        }
        this.steps.splice(idx, 1)
      },
      addStep () {
        this.steps.push({
          isNew: true,
          description: '',
          longitude: 0,
          latitude: 0
        })
      }
    }

  }
</script>

<style lang='scss'>
  .create-task {
    margin-top: 20px;
  }
</style>
