<template>
  <router-link
    :to="'task?category='+category.cid"
    class="category-card column is-4">
    <div v-if="category" transition="expand" class="category-card-content">
      <img v-bind:src="category.image_url" class="image">
      <header class="header">
        {{ category.name }}
      </header>
      <div class="content">
        <p>{{ category.description }}</p>
      </div>
    </div>
    <div v-else>
    </div>
  </router-link>
</template>

<script>
export default {
  props: ['category']
}
</script>

<style lang='scss'>
  .category-card {
    position: relative;

    .category-card-content {
      background: white;
    }

    .header {
      position: absolute;
      text-align: left;
      padding-left: 20px;
      color: white;
      font-size: 1.5em;
      top: 30px;
      left: 10px;
      right: 10px;
    }

    .content {
      color: white;
      position: absolute;
      bottom: 50px;
      left: 30px;
    }
  }
</style>
