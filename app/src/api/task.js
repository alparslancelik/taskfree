import Api from './Api'

Api.prototype.createTask = async function (newTask) {
  if (!newTask.end_time) {
    delete newTask.end_time
  }
  const { body } = await this.post('task').send(newTask)
  return body
}

Api.prototype.getTask = async function (id) {
  const { body } = await this.get(`task/${id}`)
  return body
}

Api.prototype.listTask = async function (category) {
  const url = category ? `task?category=${category}` : 'task'
  const { body } = await this.get(url)
  return body
}

Api.prototype.getPickByTaskId = async function (tid) {
  const url = `task/${tid}/pick`
  const { body } = await this.get(url)
  return body
}

Api.prototype.pickTask = async function (tid) {
  const url = `task/${tid}/pick`
  const { body } = await this.post(url).send()
  return body
}

Api.prototype.updateTask = async function (tid, task) {
  const url = `task/${tid}`
  if (!task.end_time) {
    delete task.end_time
  }
  const { body } = await this.put(url).send(task)
  return body
}

Api.prototype.getSteps = async function (tid) {
  const url = `task/${tid}/steps`
  const { body } = await this.get(url)
  return body.sort((a, b) => {
    return a.sid - b.sid
  })
}

Api.prototype.deleteTask = async function (tid) {
  const url = `task/${tid}`
  const { body } = await this.delete(url)
  return body
}

Api.prototype.deleteStep = async function (tid, sid) {
  const url = `task/${tid}/steps/${sid}`
  const { body } = await this.delete(url)
  return body
}

Api.prototype.updateStep = async function (tid, sid, step) {
  const url = `task/${tid}/steps/${sid}`
  const { body } = await this.put(url).send(step)
  return body
}

Api.prototype.countAcceptedPick = async function (tid) {
  const url = `task/${tid}/pick`
  const { body } = await this.get(url)
  return body.filter((x) => x.accepted).length
}
