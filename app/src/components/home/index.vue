<template>
  <div class="home">
    <div class="jumbotron has-text-centered">
      <h1 class="title">Search for Open Task or Create One</h1>
      <div class="columns is-mobile">
        <div class="column is-2">
          <p class="control">
            <span class="select is-medium">
              <select v-model="searchMode" placeholder="category">
                <option value="category">I need a help</option>
                <option value="task">I want to help</option>
              </select>
            </span>
          </p>
        </div>
        <div class="column is-8">
          <p class="search control is-fullwidth">
            <input type="text"
                   :debounce="500"
                   v-on:keyup.enter="search"
                   v-model="searchTerm"
                   class="input is-medium"
                   placeholder="cleaning house, walking dog"></input>
          </p>
        </div>
        <div class="column is-2">
          <button @click="search" class="button is-medium is-info">
            <i class="fa fa-search"/>
          </button>
        </div>
      </div>
    </div>
    <div class="hero category-list-container has-text-centered is-light">
      <h1 v-show="searchRequested" class="title">
        Searching {{searchMode === 'category' ? 'Category' : 'Task'}} "{{searchTerm}}"...
      </h1>
      <div class="category-list columns is-multiline">
        <category-card v-for="category in categories" v-bind:category="category">
        </category-card>
      </div>
    </div>
    <how-it-works></how-it-works>
    <statistics></statistics>
  </div>
</template>

<script>
import CategoryCard from '../category/CategoryCard'
import HowItWorks from './HowItWorks'
import Statistics from './Statistics'

export default {
  async mounted () {
    this.categories = await this.$api.listCategory()
  },
  methods: {
    search: async function () {
      if (!this.searchTerm) {
        this.categories = await this.$api.listCategory()
        this.searchRequested = false
      } else {
        this.searchRequested = true
        this.categories = await this.$api.search({
          type: this.searchMode,
          q: this.searchTerm
        })
      }
    }
  },
  watch: {
    searchTerm: async function (value) {
      this.searchRequested = true
      this.categories = await this.$api.search({
        type: this.searchMode,
        q: this.searchTerm
      })
    }
  },
  data () {
    return {
      categories: [],
      searchMode: 'category',
      searchTerm: '',
      searchRequested: false
    }
  },
  components: {
    CategoryCard,
    HowItWorks,
    Statistics
  }
}
</script>

<style lang='scss'>
  .jumbotron {
    background-color: white;
    margin-top: 10px;
    padding: 30px;

    h1 {
      font-weight: 400;
    }

    .open-jobs-link {
      font-size: 1.2em;
    }
  }
  .category-list-container {
    background-color: white;
    padding: 20px 0px;
    margin: 20px 0px;
  }
  .more-categories {
    font-size: 1.2em;
  }
</style>
