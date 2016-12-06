<template>
  <div class="columns admin-dashboard">
    <div class="column is-3">
      <aside class="menu">
        <p class="menu-label">
          Entities
        </p>
        <ul class="menu-list">
          <li><a href="#">Categories</a></li>
          <li><a href="#">Tasks</a></li>
          <li><a class="is-active" href="#">Users</a></li>
          <li><a href="#">Picks</a></li>
          <li><a href="#">Notifications</a></li>
        </ul>
        <p class="menu-label">
          View Audit Data
        </p>
        <ul class="menu-list">
          <li><a href="#">Past Month</a></li>
          <li><a href="#">Past Week</a></li>
        </ul>
      </aside>
    </div>
    <div class="column is-9">
      <nav class="pagination">
        <a class="button">Previous</a>
        <a class="button">Next page</a>
        <ul>
          <li>
            <a class="button">1</a>
          </li>
          <li>
            <a class="button">2</a>
          </li>
        </ul>
      </nav>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>E-Mail</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users">
            <td>{{ user.name.first + ' ' + user.name.last }}</td>
            <td>{{ user.email }}</td>
            <td class="is-icon">
              <a href="#">
                <i class="fa fa-edit"></i>
              </a>
            </td>
            <td class="is-icon">
              <a href="#">
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
/* global $ */

  export default {
    mounted () {
      for (var x = 0; x <= 50; x++) {
        $.ajax({
          url: 'https://randomuser.me/api/',
          dataType: 'json',
          success: (data) => {
            this.users = this.users.concat(data.results)
          }
        })
      }
    },
    data () {
      return {
        users: []
      }
    }
  }
</script>

<style lang='scss'>
  .admin-dashboard {
    margin-top: 20px;
  }
</style>
