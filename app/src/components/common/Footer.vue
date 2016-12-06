<template>
  <section class="footer columns">
    <section class="column is-half">
      <div class="copyright">Copyright &copy; 2016</div>
      <small class="authors">This site is made for educational purposes
        only and does not represent a real product</small>
    </section>
    <section class="column is-half">
      <div class="misc-links text pull-right">
        <a href="#">Contact Us</a>
        <a href="#">Terms of Conditions</a>
        <a href="#">Privacy Policy</a>
      </div>
    </section>
  </section>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
  $footer-bg: lighten(#bdc3c7, 10%);

  .footer {
    padding: 20px 30px;
    color: darken($footer-bg, 20%);

    .copyright {
      font-size: 0.9em;
    }

    .misc-links {
      a {
        margin-right: 10px;
      }
    }
  }
</style>
