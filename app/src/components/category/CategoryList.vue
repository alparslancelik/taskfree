<template>
  <div class="category-list columns is-multiline">
    <category-card v-for="categoryID in categories" v-bind:id="categoryID">
    </category-card>
  </div>
</template>

<script>
import CategoryCard from './CategoryCard'

export default {
  props: ['categories'],
  components: {
    CategoryCard
  }
}
</script>
