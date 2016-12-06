import Api from './Api'

Api.prototype.status = async function () {
  const { body } = await this.get('status')
  return body.status
}
