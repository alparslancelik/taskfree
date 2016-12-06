import superagentPromise from 'superagent-promise'
import request from 'superagent'

const API_BASE = `${window.location.origin}/api/v1`

function relativize (url) {
  return `${API_BASE}/${url}`
}

export default class Api {

  constructor () {
    this.request = superagentPromise(request, Promise)
  }

  get (url) {
    if (/Object/.test(url)) {
      console.log(JSON.stringify(url.split('/')[1]))
    }
    return this.request.get(relativize(url))
  }

  put (url, data) {
    return this.request.put(relativize(url), data)
  }

  post (url, data) {
    return this.request.post(relativize(url), data)
  }

  delete (url) {
    return this.request.del(relativize(url))
  }

  simulate (response) {
    return new Promise((resolve, reject) => {
      const timeout = Math.floor(Math.random() * 1000)

      setTimeout(() => {
        resolve({
          status: 200,
          body: response
        })
      }, timeout)
    })
  }

  install (Vue) {
    Vue.prototype.$api = this
  }
}
