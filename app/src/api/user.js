import Api from './Api'

Api.prototype.currentUser = { isSignedIn: false }

Api.prototype.setUser = async function (user) {
  this.currentUser.isAdmin = user.admin
  this.currentUser.imageUrl = user.image_url
  this.currentUser.name = user.name
  this.currentUser.email = user.email
  this.currentUser.uid = user.uid
  this.currentUser.isSignedIn = true
}

Api.prototype.refreshAuth = async function () {
  const result = await this.get('auth')
  console.log(result)
  if (result.body) {
    this.setUser(result.body)
  }
}

Api.prototype.signIn = async function ({ email, password }) {
  const result = await this.post('auth/signin').send({ email, password })
  this.setUser(result.body)
}

Api.prototype.register = async function ({ name, username, email, password }) {
  const result = await this.post('user').send({
    name,
    image_url: 'dummy',
    email,
    password,
    uname: username
  })
  this.setUser(result.body)
}

Api.prototype.signOut = async function () {
  await this.get('auth/signout')
  this.currentUser.isSignedIn = false
  this.currentUser.isAdmin = false
  this.currentUser.imageUrl = null
  this.currentUser.name = null
  this.currentUser.email = null
  this.currentUser.id = null
}

Api.prototype.getUser = async function (id) {
  const { body } = await this.get(`user/${id}`)
  return body
}

