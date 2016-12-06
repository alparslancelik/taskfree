<template>
  <div class="step columns">
    <div class="column is-12">
      <div class="box">
        <div class="columns is-mobile">
          <div class="column is-2">
            <h1 class="title is-3">{{ index + 1 }}</h1>
            <button v-show="isEditable" class="button is-danger">
              <span @click="deleteStep" class="icon">
                <i class="fa fa-trash" />
              </span>
            </button>
            <button v-show="isEditable" class="button is-info">
              <span @click="saveStep" class="icon">
                <i class="fa fa-save" />
              </span>
            </button>
            <button v-show="isEditable" class="button is-warning">
              <span class="icon">
                <i class="fa fa-map" />
              </span>
            </button>
          </div>
          <div class="column is-5">
            <textarea tr="5" :disabled="!isEditable" v-model='step.description'
              class="textarea is-fullwidth" type="text">
            </textarea>
          </div>
          <div class="column is-5">
            <h4 class="title is-4">Location</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['step', 'isEditable', 'index'],
  methods: {
    async saveStep () {
      await this.$api.updateStep(this.step)
    },
    async deleteStep () {
      await this.$parent.deleteStep(this.index)
    }
  }
}
</script>

<style lang='scss'>
  .step {
    margin-bottom: 10px !important;
  }
</style>
