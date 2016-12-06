<template>
<div>
  <app-navbar></app-navbar>
  <div class="container">
    <router-view></router-view>
    <app-footer></app-footer>
  </div>
</div>
</template>

<script>
import AppNavbar from './components/common/Navbar'
import AppFooter from './components/common/Footer'

export default {
  components: {
    AppNavbar,
    AppFooter
  }
}
</script>

<style lang='scss'>
  $family-sans-serif: 'Hammersmith One', sans-serif;
  h1,h2,h3,h4,h5,h6 {
    font-family: 'Arvo', sans-serif;
  }
  @import '~bulma';
</style>
