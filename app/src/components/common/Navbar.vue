<template>
  <section class="navbar hero" :class="navbarClass">
    <nav class="nav has-shadow">
      <div class="nav-left">
        <a class="brand nav-item is-brand">
          <router-link to='/' class="title is-3">
            <b>TF</b>
          </router-link>
        </a>
        <span v-show="isSignedIn" class="nav-item">
          <router-link to='/task/create' class="button is-primary">
            <span class="icon">
              <i class="fa fa-plus"></i>
            </span>
            <span>Create Task</span>
          </router-link>
        </span>
        <span class="nav-item" v-show="isSignedIn">
          <router-link to="/dashboard" class="is-primary">
            <span>Dashboard</span>
          </router-link>
        </span>
        <span class="nav-item">
          <router-link to="/task" class="is-primary">
            <span>Browse Tasks</span>
          </router-link>
        </span>
        <span v-show="isAdmin" class="nav-item">
          <router-link to="/admin" class="is-primary">
            <span>Admin Dashboard</span>
          </router-link>
        </span>
      </div>
      <div class="nav-right">
        <span v-show="isNotSignedIn" class="nav-item">
          <div class="button is-danger" @click="openModal">
            <span>Sign In</span>
          </div>
        </span>
        <span v-show="isSignedIn" class="nav-item">
          <span class="icon">
            <i class="fa fa-bell"></i>
          </span>
        </span>
        <span class="nav-item current-user" v-show="isSignedIn" data-jq-dropdown="#user-dropdown">
          <figure class="image is-24x24 profile-picture" v-show="currentUser.profilePicture">
            <img v-bind:src="currentUser.profilePicture" />
          </figure>
          <span class="name"> {{currentUser.name}}</span>
          <i class="fa fa-caret-down"></i>
        </span>
        <div id="user-dropdown" v-show="isSignedIn" class="jq-dropdown jq-dropdown-tip">
          <ul class="jq-dropdown-menu">
            <li><router-link to="profile" href="#">Edit Profile</router-link></li>
            <li><a @click="signOut" href="#">Sign Out</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="modal" :class='modalClass'>
      <div class="modal-background"></div>
      <div class="modal-content">
        <sign-in></sign-in>
      </div>
      <button class="modal-close" @click="closeModal"></button>
    </div>
  </section>
</template>

<script>
import SignIn from '../user/SignIn'

export default {
  data () {
    return {
      currentUser: this.$api.currentUser,
      isModalShown: false
    }
  },
  methods: {
    openModal () {
      this.isModalShown = true
    },
    closeModal () {
      this.isModalShown = false
    },
    signOut () {
      this.$api.signOut()
    }
  },
  mounted () {
    this.$api.refreshAuth()
  },
  components: {
    SignIn
  },
  computed: {
    isNotSignedIn () {
      return !this.currentUser.isSignedIn
    },
    isSignedIn () {
      return this.currentUser.isSignedIn
    },
    isAdmin () {
      return this.currentUser.isSignedIn && this.currentUser.isAdmin
    },
    modalClass () {
      return {
        'is-active':
          this.isModalShown &&
            !this.currentUser.isSignedIn
      }
    },
    navbarClass () {
      return {
        'is-danger': this.currentUser && this.currentUser.isAdmin
      }
    }
  }
}
</script>

<style lang='scss'>
  .navbar {
    padding: 0px 20px;
  }

  .current-user {
    margin-right: 40px;
  }

  #user-dropdown {
    text-align: left;
    color: black;
  }

  .search {
    width: 800px;
  }

  .current-user {
    cursor: pointer;
    text-align: left !important;
    .name, .profile-picture {
      margin-right: 5px;
    }
  }
</style>
