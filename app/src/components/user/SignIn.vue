<template>
  <div class="signin-form">
    <div class="form-header columns is-mobile">
      <div class="column hero" :class="headerClass('sign-in')"
      @click="changeMode('sign-in')">
      <h4 class="title is-5 has-text-centered">Sign In</h4>
      <div class="subtitle is-6 has-text-centered">Existing User</div>
    </div>
    <div class="column hero" :class="headerClass('register')"
    @click="changeMode('register')">
    <h4 class="title is-5 has-text-centered">Register</h4>
    <div class="subtitle is-6 has-text-centered">New User</div>
  </div>
</div>
<div class="form-content">
  <article v-show="isFailed" class="message is-danger">
    <div class="message-header">
      {{failedHeader}}
    </div>
    <div class="message-body">
      {{failedMessage}}
    </div>
  </article>
  <label v-show="isMode('register')" class="label">Name</label>
  <p v-show="isMode('register')" class="control">
    <input v-model="name" class="input" type="text" placeholder="Your Name">
  </p>
  <label v-show="isMode('register')" class="label">Username</label>
  <p v-show="isMode('register')" class="control has-icon has-icon-right">
    <input v-model="username" class="input" type="text" placeholder="Username" />
  </p>
  <label class="label">Email</label>
  <p class="control has-icon has-icon-right">
    <input v-model="email" class="input" type="text" placeholder="Email" />
  </p>
  <label class="label">Password</label>
  <p class="control has-icon has-icon-right">
    <input v-model="password" class="input" type="password" placeholder="Password" />
  </p>
  <p class="control">
    <button class="button" :class="buttonClass" @click="loginOrRegister">
      {{buttonLabel}}
    </button>
  </p>
</div>
</div>
</template>

<script>
  export default {
    data () {
      return {
        isFailed: false,
        failedHeader: '',
        failedMessage: '',
        currentUser: this.$api.currentUser,
        mode: 'sign-in', // or 'register'
        username: '',
        email: '',
        password: '',
        name: ''
      }
    },
    computed: {
      buttonClass () {
        return {
          'is-warning': this.mode === 'register'
        }
      },
      buttonLabel () {
        if (this.mode === 'register') {
          return 'Register'
        } else {
          return 'Sign In'
        }
      }
    },
    methods: {
      isMode (mode) {
        return this.mode === mode
      },
      changeMode (to) {
        this.isFailed = false
        this.mode = to
      },
      headerClass (mode) {
        return {
          'is-warning': this.mode === mode,
          'is-dark': this.mode !== mode
        }
      },
      async loginOrRegister () {
        try {
          if (this.mode === 'sign-in') {
            await this.$api.signIn({
              email: this.email,
              password: this.password
            })
          } else {
            await this.$api.register({
              name: this.name,
              username: this.username,
              email: this.email,
              password: this.password
            })
          }
        } catch (e) {
          this.isFailed = true
          if (this.mode === 'sign-in') {
            this.failedHeader = 'Sign In Failed'
            this.failedMessage = 'Invalid E-mail or Password, please try again'
          }
        } finally {
          if (!this.isFailed &&
            typeof this.$parent.closeModal === 'function') {
            this.$parent.closeModal()
          }
          window.location.reload()
        }
        this.email = ''
        this.password = ''
        this.name = ''
        this.username = ''
      }
    }
  }
</script>

<style lang="scss">
  .signin-form {
    background-color: white;
    margin-top: 20px;
    max-width: 500px;
  }

  .form-header {
    padding: 0px 10px;
  }

  .form-content {
    padding: 30px;
  }
</style>
