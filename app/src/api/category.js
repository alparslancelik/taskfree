import Api from './Api'

Api.prototype.listCategory = async function ({ limit, resolve } = {}) {
  limit = limit || Infinity
  const result = await this.get('category')
  console.log(result.body)
  result.body.forEach(x => { x.image_url = 'https://res.cloudinary.com/taskrabbit-com/image/upload/c_fill,h_420,w_970/v1466541181/e3ov5jgkkegllzdwhwbl.jpg'})
  return result.body
}

Api.prototype.getCategory = async function (id) {
  const result = await this.get(`category/${id}`)
  result.body.image_url = 'https://res.cloudinary.com/taskrabbit-com/image/upload/c_fill,h_420,w_970/v1466541181/e3ov5jgkkegllzdwhwbl.jpg'
  return result.body
}

Api.prototype.search = async function ({ type, q }) {
  const result = await this.get('search').query({ type, q })
  result.body.forEach(x => { x.image_url = 'https://res.cloudinary.com/taskrabbit-com/image/upload/c_fill,h_420,w_970/v1466541181/e3ov5jgkkegllzdwhwbl.jpg'})
  return result.body
}
